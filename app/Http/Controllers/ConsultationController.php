<?php

namespace App\Http\Controllers;

use App\Events\AdminDashboardActivity;
use App\Events\AdminInboxActivity;
use App\Models\AnalyticsEvent;
use App\Models\Consultation;
use App\Models\ConsultationGuest;
use App\Models\ConsultationHistoryOwner;
use App\Support\PatientAccessCookie;
use App\Support\PatientHistoryAccess;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class ConsultationController extends Controller
{
    public function entry(
        Request $request,
        PatientAccessCookie $accessCookie,
        PatientHistoryAccess $historyAccess
    ): View|RedirectResponse {
        $guest = $accessCookie->restore($request);

        if ($guest) {
            $accessCookie->refresh($request, $guest);
        }

        if (! $guest) {
            return redirect()->route(
                'consultation.create'
            );
        }

        $guest->loadMissing('historyOwner');
        $owner = $guest->historyOwner;

        $latestConsultation = ($owner
            ? $owner->consultations()
            : $guest->consultations())
            ->with('lastMessage')
            ->orderByRaw(
                'COALESCE(consultations.last_message_at, consultations.created_at) DESC'
            )
            ->orderByDesc('consultations.id')
            ->first();

        if (! $latestConsultation) {
            return redirect()->route(
                'consultation.create'
            );
        }

        if (! $owner) {
            return view('consultation.history-access', [
                'mode' => 'setup',
                'latestConsultation' => $latestConsultation,
            ]);
        }

        if (! $historyAccess->isUnlocked($request, $owner)) {
            return view('consultation.history-access', [
                'mode' => 'unlock',
                'latestConsultation' => $latestConsultation,
            ]);
        }

        $consultationsQuery = $owner
            ->consultations()
            ->with('lastMessage')
            ->orderByRaw(
                'COALESCE(consultations.last_message_at, consultations.created_at) DESC'
            )
            ->orderByDesc('consultations.id');

        $recentConsultations = (clone $consultationsQuery)
            ->limit(6)
            ->get();

        $activeConsultations = (clone $consultationsQuery)
            ->where('consultations.status', 'aktif')
            ->limit(4)
            ->get();

        $consultationTotal = $owner
            ->consultations()
            ->count();

        $activeTotal = $owner
            ->consultations()
            ->where('consultations.status', 'aktif')
            ->count();

        return view('consultation.entry', [
            'latestConsultation' => $latestConsultation,
            'recentConsultations' => $recentConsultations,
            'activeConsultations' => $activeConsultations,
            'consultationTotal' => $consultationTotal,
            'activeTotal' => $activeTotal,
            'completedTotal' => max(
                0,
                $consultationTotal - $activeTotal
            ),
            'deviceExpiresAt' => $guest->expires_at,
            'patientHistoryDays' => (int) config(
                'consultation.patient_history_days',
                60
            ),
        ]);
    }

    public function lockHistory(
        Request $request,
        PatientHistoryAccess $historyAccess
    ): RedirectResponse {
        $historyAccess->lock($request);
        $request->session()->regenerateToken();

        return redirect()
            ->route('consultation.entry')
            ->with(
                'status',
                'Riwayat konsultasi telah dikunci pada sesi ini.'
            );
    }

    public function history(
        Request $request,
        PatientAccessCookie $accessCookie,
        PatientHistoryAccess $historyAccess
    ): View|RedirectResponse {
        $guest = $accessCookie->restore($request);

        if (! $guest) {
            return redirect()->route(
                'consultation.create'
            );
        }

        $accessCookie->refresh($request, $guest);
        $guest->loadMissing('historyOwner');
        $owner = $guest->historyOwner;

        if (! $owner) {
            return redirect()->route(
                'consultation.entry'
            );
        }

        if (! $historyAccess->isUnlocked($request, $owner)) {
            return redirect()
                ->route('consultation.entry')
                ->with(
                    'warning',
                    'Masukkan Password Riwayat untuk membuka daftar konsultasi.'
                );
        }

        $status = (string) $request->query(
            'status',
            'semua'
        );

        if (! in_array(
            $status,
            ['semua', 'aktif', 'selesai'],
            true
        )) {
            $status = 'semua';
        }

        $baseQuery = $owner
            ->consultations()
            ->with('lastMessage');

        $consultationTotal = (clone $baseQuery)->count();
        $activeTotal = (clone $baseQuery)
            ->where('consultations.status', 'aktif')
            ->count();
        $completedTotal = (clone $baseQuery)
            ->where('consultations.status', 'selesai')
            ->count();

        $archiveCutoff = now()->subDays(
            max(
                1,
                (int) config(
                    'consultation.patient_history_days',
                    60
                )
            )
        );
        $archivedTotal = (clone $baseQuery)
            ->where('consultations.status', 'selesai')
            ->where(function ($query) use ($archiveCutoff): void {
                $query
                    ->where(
                        'consultations.closed_at',
                        '<=',
                        $archiveCutoff
                    )
                    ->orWhere(function ($legacy) use ($archiveCutoff): void {
                        $legacy
                            ->whereNull('consultations.closed_at')
                            ->where(
                                'consultations.updated_at',
                                '<=',
                                $archiveCutoff
                            );
                    });
            })
            ->count();

        $consultations = $baseQuery
            ->when(
                $status !== 'semua',
                fn ($query) => $query->where(
                    'consultations.status',
                    $status
                )
            )
            ->orderByRaw(
                'COALESCE(consultations.last_message_at, consultations.created_at) DESC'
            )
            ->orderByDesc('consultations.id')
            ->paginate(10)
            ->withQueryString();

        return view('consultation.history', [
            'consultations' => $consultations,
            'selectedStatus' => $status,
            'consultationTotal' => $consultationTotal,
            'activeTotal' => $activeTotal,
            'completedTotal' => $completedTotal,
            'archivedTotal' => $archivedTotal,
            'patientHistoryDays' => (int) config(
                'consultation.patient_history_days',
                60
            ),
        ]);
    }

    public function create(
        Request $request,
        PatientAccessCookie $accessCookie,
        PatientHistoryAccess $historyAccess
    ): View|RedirectResponse {
        $guest = $accessCookie->restore($request);

        if ($guest) {
            $accessCookie->refresh($request, $guest);
            $guest->loadMissing('historyOwner');
            $hasConsultations = $guest->historyOwner
                ? $guest->historyOwner
                    ->consultations()
                    ->exists()
                : $guest->consultations()->exists();

            if (
                $hasConsultations
                && (
                    ! $guest->historyOwner
                    || ! $historyAccess->isUnlocked(
                        $request,
                        $guest->historyOwner
                    )
                )
            ) {
                return redirect()->route(
                    'consultation.entry'
                );
            }
        }

        AnalyticsEvent::recordOnce(
            $request,
            'consultation_form_viewed',
            metadata: [
                'source' => 'consultation_form',
            ]
        );

        return view('consultation.form', [
            'requiresHistoryPassword' =>
                ! $guest?->historyOwner,
        ]);
    }

    public function setupHistoryPassword(
        Request $request,
        PatientAccessCookie $accessCookie,
        PatientHistoryAccess $historyAccess
    ): RedirectResponse {
        $guest = $accessCookie->restore($request);

        abort_unless(
            $guest
            && $guest->consultations()->exists(),
            404
        );

        $guest->loadMissing('historyOwner');

        if ($guest->historyOwner) {
            return redirect()->route(
                'consultation.entry'
            );
        }

        $validated = $request->validate([
            'password_riwayat' => $this->passwordRules(
                confirmed: true
            ),
        ], $this->passwordMessages());

        $owner = DB::transaction(
            function () use (
                $guest,
                $validated
            ): ConsultationHistoryOwner {
                $lockedGuest = ConsultationGuest::query()
                    ->lockForUpdate()
                    ->findOrFail($guest->id);

                if ($lockedGuest->history_owner_id) {
                    return $lockedGuest
                        ->historyOwner()
                        ->firstOrFail();
                }

                $owner = ConsultationHistoryOwner::create([
                    'password_hash' => Hash::make(
                        $validated['password_riwayat']
                    ),
                    'password_set_at' => now(),
                ]);

                $lockedGuest->historyOwner()->associate(
                    $owner
                );
                $lockedGuest->save();

                return $owner;
            }
        );

        $request->session()->regenerate();
        $historyAccess->unlock($request, $owner);

        return redirect()
            ->route('consultation.entry')
            ->with(
                'status',
                'Password Riwayat berhasil dibuat.'
            );
    }

    public function unlockHistory(
        Request $request,
        PatientAccessCookie $accessCookie,
        PatientHistoryAccess $historyAccess
    ): RedirectResponse {
        $guest = $accessCookie->restore($request);

        abort_unless($guest, 404);

        $guest->loadMissing('historyOwner');
        $owner = $guest->historyOwner;

        abort_unless($owner, 404);

        $validated = $request->validate([
            'password_riwayat' => [
                'required',
                'string',
                'max:128',
            ],
        ], [
            'password_riwayat.required' =>
                'Masukkan Password Riwayat.',
        ]);

        if (! $historyAccess->verifyPassword(
            $owner,
            $validated['password_riwayat']
        )) {
            throw ValidationException::withMessages([
                'password_riwayat' =>
                    'Password Riwayat tidak sesuai atau akses sedang dikunci sementara.',
            ]);
        }

        $request->session()->regenerate();
        $historyAccess->unlock($request, $owner);

        return redirect()
            ->route('consultation.entry')
            ->with(
                'status',
                'Riwayat konsultasi berhasil dibuka.'
            );
    }

    public function store(
        Request $request,
        PatientAccessCookie $accessCookie,
        PatientHistoryAccess $historyAccess
    ): RedirectResponse {
        $guest = $accessCookie->restore($request);
        $guest?->loadMissing('historyOwner');
        $owner = $guest?->historyOwner;
        $requiresHistoryPassword = ! $owner;

        if (
            $owner
            && ! $historyAccess->isUnlocked($request, $owner)
        ) {
            return redirect()
                ->route('consultation.entry')
                ->withErrors([
                    'password_riwayat' =>
                        'Masukkan Password Riwayat sebelum membuat konsultasi baru.',
                ]);
        }

        $rules = [
            'nama' => [
                'required',
                'string',
                'max:100',
            ],
            'umur' => [
                'required',
                'integer',
                'min:1',
                'max:120',
            ],
            'no_hp' => [
                'required',
                'string',
                'max:25',
                'regex:/^[0-9+\-\s()]+$/',
            ],
            'jenis_konsultasi' => [
                'required',
                'in:resep,non_resep',
            ],
        ];

        if ($requiresHistoryPassword) {
            $rules['password_riwayat'] =
                $this->passwordRules(confirmed: true);
        }

        $validated = $request->validate(
            $rules,
            $this->passwordMessages()
        );

        [$consultation, $owner] = DB::transaction(
            function () use (
                $request,
                $validated,
                $guest,
                $owner,
                $accessCookie
            ): array {
                $activeGuest = $guest;

                if (! $activeGuest) {
                    $authenticatedGuest = Auth::guard(
                        'patient'
                    )->user();

                    if ($authenticatedGuest) {
                        Auth::guard('patient')->logout();
                    }

                    $activeGuest = ConsultationGuest::create([
                        'public_id' => (string) Str::uuid(),
                        'last_seen_at' => now(),
                        'expires_at' => $accessCookie->expiresAt(),
                    ]);

                    Auth::guard('patient')->login(
                        $activeGuest
                    );
                } else {
                    $activeGuest->forceFill([
                        'last_seen_at' => now(),
                        'expires_at' => $accessCookie->expiresAt(),
                        'revoked_at' => null,
                    ])->save();
                }

                $activeOwner = $owner;

                if (! $activeOwner) {
                    $activeOwner = ConsultationHistoryOwner::create([
                        'password_hash' => Hash::make(
                            $validated['password_riwayat']
                        ),
                        'password_set_at' => now(),
                    ]);

                    $activeGuest->historyOwner()->associate(
                        $activeOwner
                    );
                    $activeGuest->save();
                }

                $consultation = new Consultation([
                    'nama' => $validated['nama'],
                    'umur' => $validated['umur'],
                    'no_hp' => $validated['no_hp'],
                    'jenis_konsultasi' =>
                        $validated['jenis_konsultasi'],
                    'status' => 'aktif',
                ]);

                $consultation->guest()->associate(
                    $activeGuest
                );

                $consultation->save();

                AnalyticsEvent::recordOnce(
                    $request,
                    'consultation_created',
                    $consultation,
                    [
                        'type' =>
                            $consultation
                                ->jenis_konsultasi,
                    ],
                    'consultation:'
                        .$consultation->id
                );

                return [$consultation, $activeOwner];
            }
        );

        $request->session()->regenerate();
        $historyAccess->unlock($request, $owner);

        try {
            $freshConsultation = $consultation->fresh([
                'lastMessage',
            ]);

            event(
                new AdminDashboardActivity(
                    $freshConsultation,
                    'consultation_created'
                )
            );

            event(
                new AdminInboxActivity(
                    $freshConsultation,
                    'consultation_created'
                )
            );
        } catch (Throwable $exception) {
            Log::warning(
                'Notifikasi konsultasi baru gagal dikirim.',
                [
                    'consultation_id' =>
                        $consultation->id,
                    'exception' =>
                        $exception::class,
                ]
            );
        }

        return redirect()
            ->route(
                'chat.show',
                $consultation
            )
            ->withCookie(
                $accessCookie->make(
                    $request,
                    $consultation->guest
                )
            );
    }

    private function passwordRules(bool $confirmed): array
    {
        $rules = [
            'required',
            'string',
            'min:'.max(
                8,
                (int) config(
                    'consultation.history_password_min_length',
                    10
                )
            ),
            'max:128',
        ];

        if ($confirmed) {
            $rules[] = 'confirmed';
        }

        return $rules;
    }

    private function passwordMessages(): array
    {
        $minimum = max(
            8,
            (int) config(
                'consultation.history_password_min_length',
                10
            )
        );

        return [
            'password_riwayat.required' =>
                'Password Riwayat wajib diisi.',
            'password_riwayat.min' =>
                'Password Riwayat minimal '.$minimum.' karakter.',
            'password_riwayat.max' =>
                'Password Riwayat maksimal 128 karakter.',
            'password_riwayat.confirmed' =>
                'Konfirmasi Password Riwayat tidak sesuai.',
        ];
    }
}
