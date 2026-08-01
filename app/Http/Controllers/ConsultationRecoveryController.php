<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\ConsultationDeviceRecovery;
use App\Models\ConsultationGuest;
use App\Models\ConsultationHistoryOwner;
use App\Support\PatientAccessCookie;
use App\Support\PatientHistoryAccess;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ConsultationRecoveryController extends Controller
{
    private const PENDING_SESSION_KEY =
        'md_farma_consultation_recovery';

    public function show(
        Request $request,
        PatientAccessCookie $accessCookie
    ): View|RedirectResponse {
        if ($accessCookie->restore($request)) {
            return redirect()->route('consultation.entry');
        }

        $request->session()->forget(
            self::PENDING_SESSION_KEY
        );

        return view('consultation.recovery');
    }

    public function verify(
        Request $request,
        PatientAccessCookie $accessCookie,
        PatientHistoryAccess $historyAccess
    ): RedirectResponse {
        if ($accessCookie->restore($request)) {
            return redirect()->route('consultation.entry');
        }

        $validated = $request->validate([
            'no_hp' => [
                'required',
                'string',
                'max:25',
                'regex:/^[0-9+\-\s()]+$/',
            ],
            'password_riwayat' => [
                'required',
                'string',
                'max:128',
            ],
            'tanggal_konsultasi_terakhir' => [
                'required',
                'date_format:Y-m-d',
                'before_or_equal:'.now()
                    ->timezone($this->timezone())
                    ->format('Y-m-d'),
            ],
        ], [
            'no_hp.required' =>
                'Masukkan nomor HP yang pernah digunakan.',
            'no_hp.regex' =>
                'Format nomor HP tidak valid.',
            'password_riwayat.required' =>
                'Masukkan Password Riwayat.',
            'tanggal_konsultasi_terakhir.required' =>
                'Masukkan tanggal konsultasi terakhir.',
            'tanggal_konsultasi_terakhir.date_format' =>
                'Format tanggal konsultasi tidak valid.',
            'tanggal_konsultasi_terakhir.before_or_equal' =>
                'Tanggal konsultasi tidak boleh melewati hari ini.',
        ]);

        $candidate = $this->findCandidate(
            $validated['no_hp'],
            $validated['tanggal_konsultasi_terakhir']
        );

        if (! $candidate) {
            Hash::check(
                $validated['password_riwayat'],
                '$2y$12$NnM4J4C6LkX9c8dcSeCT0unSIQI3j0ULCTgz7MBJWHVQqHbWs0Pka'
            );

            throw $this->recoveryFailed();
        }

        if (! $historyAccess->verifyPassword(
            $candidate['owner'],
            $validated['password_riwayat']
        )) {
            throw $this->recoveryFailed();
        }

        $request->session()->regenerate();
        $request->session()->put(
            self::PENDING_SESSION_KEY,
            [
                'owner_id' =>
                    (int) $candidate['owner']->getKey(),
                'consultation_id' =>
                    (int) $candidate['consultation']->getKey(),
                'phone_hash' => $this->phoneFingerprint(
                    $validated['no_hp']
                ),
                'expires_at' => now()
                    ->addMinutes(
                        max(
                            5,
                            (int) config(
                                'consultation.recovery_confirmation_minutes',
                                10
                            )
                        )
                    )
                    ->timestamp,
            ]
        );

        return redirect()->route(
            'consultation.recovery.confirm.show'
        );
    }

    public function confirmation(
        Request $request,
        PatientAccessCookie $accessCookie
    ): View|RedirectResponse {
        if ($accessCookie->restore($request)) {
            return redirect()->route('consultation.entry');
        }

        $pending = $this->pendingRecovery($request);

        if (! $pending) {
            return redirect()
                ->route('consultation.recovery.show')
                ->with(
                    'warning',
                    'Sesi pemulihan telah berakhir. Masukkan kembali data pemulihan.'
                );
        }

        $consultation = Consultation::query()
            ->whereKey($pending['consultation_id'])
            ->whereHas(
                'guest',
                fn ($query) => $query->where(
                    'history_owner_id',
                    $pending['owner_id']
                )
            )
            ->first();

        if (! $consultation) {
            $request->session()->forget(
                self::PENDING_SESSION_KEY
            );

            return redirect()
                ->route('consultation.recovery.show')
                ->with(
                    'warning',
                    'Riwayat tidak dapat dipastikan. Silakan ulangi proses pemulihan.'
                );
        }

        return view('consultation.recovery-confirm', [
            'maskedName' => $this->maskName(
                $consultation->nama
            ),
            'maskedPhone' => $this->maskPhone(
                $consultation->no_hp
            ),
            'consultationDate' => $consultation
                ->created_at
                ->copy()
                ->timezone($this->timezone())
                ->locale('id')
                ->translatedFormat('d F Y'),
            'consultationType' =>
                $consultation->jenis_konsultasi === 'resep'
                    ? 'Resep dokter'
                    : 'Tanpa resep',
        ]);
    }

    public function confirm(
        Request $request,
        PatientAccessCookie $accessCookie,
        PatientHistoryAccess $historyAccess
    ): RedirectResponse {
        if ($accessCookie->restore($request)) {
            return redirect()->route('consultation.entry');
        }

        $request->validate([
            'confirm_history' => [
                'accepted',
            ],
        ], [
            'confirm_history.accepted' =>
                'Konfirmasikan bahwa riwayat tersamarkan tersebut milik Anda.',
        ]);

        $pending = $this->pendingRecovery($request);

        if (! $pending) {
            return redirect()
                ->route('consultation.recovery.show')
                ->with(
                    'warning',
                    'Sesi pemulihan telah berakhir. Masukkan kembali data pemulihan.'
                );
        }

        [$owner, $guest] = DB::transaction(
            function () use ($pending): array {
                $owner = ConsultationHistoryOwner::query()
                    ->lockForUpdate()
                    ->find($pending['owner_id']);

                $consultation = Consultation::query()
                    ->whereKey($pending['consultation_id'])
                    ->whereHas(
                        'guest',
                        fn ($query) => $query->where(
                            'history_owner_id',
                            $pending['owner_id']
                        )
                    )
                    ->lockForUpdate()
                    ->first();

                if (! $owner || ! $consultation) {
                    throw ValidationException::withMessages([
                        'recovery' =>
                            'Riwayat tidak dapat dipastikan. Ulangi proses pemulihan.',
                    ]);
                }

                $guest = ConsultationGuest::create([
                    'public_id' => (string) Str::uuid(),
                    'history_owner_id' => $owner->id,
                    'last_seen_at' => now(),
                    'expires_at' => now()->addDays(
                        max(
                            1,
                            (int) config(
                                'consultation.patient_device_days',
                                90
                            )
                        )
                    ),
                ]);

                ConsultationDeviceRecovery::create([
                    'history_owner_id' => $owner->id,
                    'source_consultation_id' =>
                        $consultation->id,
                    'new_guest_id' => $guest->id,
                    'recovery_method' =>
                        'phone_password_last_date',
                    'phone_hash' => $pending['phone_hash'],
                    'recovered_at' => now(),
                ]);

                return [$owner, $guest];
            }
        );

        Auth::guard('patient')->login($guest);
        $request->session()->regenerate();
        $request->session()->forget(
            self::PENDING_SESSION_KEY
        );
        $historyAccess->unlock($request, $owner);

        return redirect()
            ->route('consultation.entry')
            ->with(
                'status',
                'Riwayat berhasil dipulihkan dan perangkat ini telah ditautkan.'
            )
            ->withCookie(
                $accessCookie->make($request, $guest)
            );
    }

    private function findCandidate(
        string $phone,
        string $lastConsultationDate
    ): ?array {
        $normalizedPhone = $this->normalizePhone($phone);

        if ($normalizedPhone === '') {
            return null;
        }

        $timezone = $this->timezone();
        $start = Carbon::createFromFormat(
            'Y-m-d',
            $lastConsultationDate,
            $timezone
        )
            ->startOfDay()
            ->utc();
        $end = $start
            ->copy()
            ->timezone($timezone)
            ->endOfDay()
            ->utc();

        $ownerIds = Consultation::query()
            ->select([
                'consultations.no_hp',
                'consultation_guests.history_owner_id',
            ])
            ->join(
                'consultation_guests',
                'consultation_guests.id',
                '=',
                'consultations.guest_id'
            )
            ->whereNotNull(
                'consultation_guests.history_owner_id'
            )
            ->whereBetween(
                'consultations.created_at',
                [$start, $end]
            )
            ->orderByDesc('consultations.id')
            ->get()
            ->filter(
                fn ($row) => $this->normalizePhone(
                    (string) $row->no_hp
                ) === $normalizedPhone
            )
            ->pluck('history_owner_id')
            ->unique()
            ->values();

        if ($ownerIds->count() !== 1) {
            return null;
        }

        $owner = ConsultationHistoryOwner::query()
            ->find($ownerIds->first());

        if (! $owner) {
            return null;
        }

        $latestConsultation = $owner
            ->consultations()
            ->orderByDesc('consultations.created_at')
            ->orderByDesc('consultations.id')
            ->first();

        if (! $latestConsultation) {
            return null;
        }

        $latestDate = $latestConsultation
            ->created_at
            ->copy()
            ->timezone($timezone)
            ->format('Y-m-d');

        if (
            $latestDate !== $lastConsultationDate
            || $this->normalizePhone(
                $latestConsultation->no_hp
            ) !== $normalizedPhone
        ) {
            return null;
        }

        return [
            'owner' => $owner,
            'consultation' => $latestConsultation,
        ];
    }

    private function pendingRecovery(
        Request $request
    ): ?array {
        $pending = $request->session()->get(
            self::PENDING_SESSION_KEY
        );

        if (
            ! is_array($pending)
            || empty($pending['owner_id'])
            || empty($pending['consultation_id'])
            || empty($pending['phone_hash'])
            || empty($pending['expires_at'])
            || (int) $pending['expires_at'] <= now()->timestamp
        ) {
            $request->session()->forget(
                self::PENDING_SESSION_KEY
            );

            return null;
        }

        return $pending;
    }

    private function recoveryFailed(): ValidationException
    {
        return ValidationException::withMessages([
            'recovery' =>
                'Data pemulihan tidak sesuai atau riwayat tidak dapat dipastikan. Periksa kembali data yang dimasukkan.',
        ]);
    }

    private function phoneFingerprint(string $phone): string
    {
        return hash_hmac(
            'sha256',
            $this->normalizePhone($phone),
            (string) config('app.key')
        );
    }

    private function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        if (str_starts_with($digits, '62')) {
            return '0'.substr($digits, 2);
        }

        if (str_starts_with($digits, '8')) {
            return '0'.$digits;
        }

        return $digits;
    }

    private function maskName(string $name): string
    {
        return collect(
            preg_split('/\s+/', trim($name)) ?: []
        )
            ->filter()
            ->map(function (string $part): string {
                $length = mb_strlen($part);

                if ($length <= 1) {
                    return mb_substr($part, 0, 1).'*';
                }

                return mb_substr($part, 0, 1)
                    .str_repeat('*', max(3, $length - 1));
            })
            ->implode(' ');
    }

    private function maskPhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';
        $length = strlen($digits);

        if ($length <= 6) {
            return str_repeat('*', max(4, $length));
        }

        return substr($digits, 0, 3)
            .str_repeat('*', max(4, $length - 6))
            .substr($digits, -3);
    }

    private function timezone(): string
    {
        return (string) config(
            'analytics.timezone',
            'Asia/Jakarta'
        );
    }
}
