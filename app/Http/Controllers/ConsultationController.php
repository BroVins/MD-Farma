<?php

namespace App\Http\Controllers;

use App\Events\AdminDashboardActivity;
use App\Events\AdminInboxActivity;
use App\Models\AnalyticsEvent;
use App\Models\Consultation;
use App\Models\ConsultationGuest;
use App\Models\ConsultationHistoryOwner;
use App\Models\ConsultationPatientProfile;
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
            ->with(['lastMessage', 'patientProfile'])
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
            ->with(['lastMessage', 'patientProfile'])
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

        $activeDeviceTotal = $owner
            ->devices()
            ->whereNull('consultation_guests.revoked_at')
            ->where('consultation_guests.expires_at', '>', now())
            ->count();

        $patientProfileTotal = $owner
            ->patientProfiles()
            ->count();

        return view('consultation.entry', [
            'latestConsultation' => $latestConsultation,
            'recentConsultations' => $recentConsultations,
            'activeConsultations' => $activeConsultations,
            'consultationTotal' => $consultationTotal,
            'activeTotal' => $activeTotal,
            'activeDeviceTotal' => $activeDeviceTotal,
            'patientProfileTotal' => $patientProfileTotal,
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

        $profiles = $owner
            ->patientProfiles()
            ->orderByDesc('is_default')
            ->orderBy('name')
            ->get();

        $selectedProfile = (string) $request->query(
            'profil',
            'semua'
        );

        if (
            $selectedProfile !== 'semua'
            && ! $profiles->contains(
                'public_id',
                $selectedProfile
            )
        ) {
            $selectedProfile = 'semua';
        }

        $baseQuery = $owner
            ->consultations()
            ->with([
                'lastMessage',
                'patientProfile',
                'latestArchiveCopyRequest',
            ]);

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
                $selectedProfile !== 'semua',
                fn ($query) => $query->whereHas(
                    'patientProfile',
                    fn ($profileQuery) => $profileQuery->where(
                        'public_id',
                        $selectedProfile
                    )
                )
            )
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
            'selectedProfile' => $selectedProfile,
            'profiles' => $profiles,
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

        $profiles = $guest?->historyOwner
            ? $guest->historyOwner
                ->patientProfiles()
                ->orderByDesc('is_default')
                ->orderByDesc('last_used_at')
                ->orderBy('name')
                ->get()
            : collect();

        return view('consultation.form', [
            'requiresHistoryPassword' =>
                ! $guest?->historyOwner,
            'profiles' => $profiles,
            'relationshipOptions' =>
                ConsultationPatientProfile::relationshipOptions(),
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
                    $existingOwner = $lockedGuest
                        ->historyOwner()
                        ->firstOrFail();

                    $this->attachLegacyConsultationsToProfiles(
                        $lockedGuest,
                        $existingOwner
                    );

                    return $existingOwner;
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

                $this->attachLegacyConsultationsToProfiles(
                    $lockedGuest,
                    $owner
                );

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

        $hasProfiles = $owner
            ? $owner->patientProfiles()->exists()
            : false;
        $profileChoice = (string) $request->input(
            'profile_choice',
            'new'
        );
        $createsNewProfile = ! $owner
            || ! $hasProfiles
            || $profileChoice === 'new';

        $rules = [
            'jenis_konsultasi' => [
                'required',
                'in:resep,non_resep',
            ],
            'privacy_consent' => [
                'accepted',
            ],
        ];

        if ($owner && $hasProfiles) {
            $rules['profile_choice'] = [
                'required',
                'string',
                'max:40',
            ];
        }

        if ($createsNewProfile) {
            $rules = array_merge(
                $rules,
                $this->patientProfileRules()
            );
        }

        if ($requiresHistoryPassword) {
            $rules['password_riwayat'] =
                $this->passwordRules(confirmed: true);
        }

        $validated = $request->validate(
            $rules,
            array_merge(
                $this->passwordMessages(),
                $this->patientProfileMessages(),
                [
                    'privacy_consent.accepted' =>
                        'Anda harus menyetujui pemrosesan data dan kebijakan privasi sebelum memulai konsultasi.',
                ]
            )
        );

        if (
            $owner
            && $hasProfiles
            && ! $createsNewProfile
            && ! Str::isUuid($profileChoice)
        ) {
            throw ValidationException::withMessages([
                'profile_choice' =>
                    'Profil pasien yang dipilih tidak valid.',
            ]);
        }

        [$consultation, $owner, $activeGuest] = DB::transaction(
            function () use (
                $request,
                $validated,
                $guest,
                $owner,
                $accessCookie,
                $createsNewProfile,
                $profileChoice
            ): array {
                $activeGuest = $guest;

                if (! $activeGuest) {
                    $activeGuest = ConsultationGuest::create([
                        'public_id' => (string) Str::uuid(),
                        'last_seen_at' => now(),
                        'expires_at' => $accessCookie->expiresAt(),
                    ]);
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

                if ($createsNewProfile) {
                    $hasExistingProfiles = $activeOwner
                        ->patientProfiles()
                        ->lockForUpdate()
                        ->first() !== null;

                    $profile = $activeOwner
                        ->patientProfiles()
                        ->create([
                            'name' => $validated['nama'],
                            'age' => $validated['umur'],
                            'phone' => $validated['no_hp'],
                            'relationship' =>
                                $validated['hubungan'],
                            'is_default' => ! $hasExistingProfiles,
                            'last_used_at' => now(),
                        ]);
                } else {
                    $profile = $activeOwner
                        ->patientProfiles()
                        ->where('public_id', $profileChoice)
                        ->lockForUpdate()
                        ->first();

                    if (! $profile) {
                        throw ValidationException::withMessages([
                            'profile_choice' =>
                                'Profil pasien tidak ditemukan pada riwayat Anda.',
                        ]);
                    }

                    $profile->forceFill([
                        'last_used_at' => now(),
                    ])->save();
                }

                $consultation = new Consultation([
                    'patient_profile_id' => $profile->id,
                    'nama' => $profile->name,
                    'umur' => $profile->age,
                    'no_hp' => $profile->phone,
                    'jenis_konsultasi' =>
                        $validated['jenis_konsultasi'],
                    'privacy_consent_at' => now(),
                    'privacy_policy_version' => (string) config(
                        'mdfarma.privacy_policy_version',
                        '2026-08-01'
                    ),
                    'privacy_consent_text' => (string) config(
                        'mdfarma.privacy_consent_text'
                    ),
                    'privacy_consent_ip_hash' =>
                        $this->consentFingerprint($request->ip()),
                    'privacy_consent_user_agent_hash' =>
                        $this->consentFingerprint(
                            $request->userAgent()
                        ),
                    'status' => 'aktif',
                ]);

                $consultation->guest()->associate(
                    $activeGuest
                );
                $consultation->patientProfile()->associate(
                    $profile
                );
                $consultation->save();

                AnalyticsEvent::recordOnce(
                    $request,
                    'consultation_created',
                    $consultation,
                    [
                        'type' =>
                            $consultation->jenis_konsultasi,
                        'patient_relationship' =>
                            $profile->relationship,
                    ],
                    'consultation:'
                        .$consultation->id
                );

                return [
                    $consultation,
                    $activeOwner,
                    $activeGuest,
                ];
            }
        );

        Auth::guard('patient')->login($activeGuest);
        $request->session()->regenerate();
        $historyAccess->unlock($request, $owner);

        try {
            $freshConsultation = $consultation->fresh([
                'lastMessage',
                'patientProfile',
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
                    $activeGuest
                )
            );
    }

    private function attachLegacyConsultationsToProfiles(
        ConsultationGuest $guest,
        ConsultationHistoryOwner $owner
    ): void {
        $consultations = $guest
            ->consultations()
            ->whereNull('patient_profile_id')
            ->orderBy('id')
            ->get();

        foreach ($consultations as $consultation) {
            $profile = $owner
                ->patientProfiles()
                ->where('name', $consultation->nama)
                ->where('age', $consultation->umur)
                ->where('phone', $consultation->no_hp)
                ->first();

            if (! $profile) {
                $profile = $owner
                    ->patientProfiles()
                    ->create([
                        'name' => $consultation->nama,
                        'age' => $consultation->umur,
                        'phone' => $consultation->no_hp,
                        'relationship' => 'lainnya',
                        'last_used_at' =>
                            $consultation->last_message_at
                            ?? $consultation->created_at,
                    ]);
            }

            $consultation->patientProfile()->associate(
                $profile
            );
            $consultation->saveQuietly();
        }

        if (! $owner->patientProfiles()->where('is_default', true)->exists()) {
            $defaultProfile = $owner
                ->patientProfiles()
                ->orderByDesc('last_used_at')
                ->orderByDesc('id')
                ->first();

            if ($defaultProfile) {
                $defaultProfile->update([
                    'is_default' => true,
                    'relationship' => 'saya',
                ]);
            }
        }
    }

    private function consentFingerprint(?string $value): ?string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        $key = (string) config('app.key');

        return hash_hmac(
            'sha256',
            $value,
            $key !== '' ? $key : 'md-farma-consent'
        );
    }

    private function patientProfileRules(): array
    {
        return [
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
            'hubungan' => [
                'required',
                'in:'.implode(
                    ',',
                    array_keys(
                        ConsultationPatientProfile::relationshipOptions()
                    )
                ),
            ],
        ];
    }

    private function patientProfileMessages(): array
    {
        return [
            'profile_choice.required' =>
                'Pilih pasien yang akan dikonsultasikan.',
            'nama.required' => 'Nama pasien wajib diisi.',
            'umur.required' => 'Umur pasien wajib diisi.',
            'umur.integer' => 'Umur pasien harus berupa angka.',
            'umur.min' => 'Umur pasien minimal 1 tahun.',
            'umur.max' => 'Umur pasien maksimal 120 tahun.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'no_hp.regex' => 'Format nomor HP tidak sesuai.',
            'hubungan.required' =>
                'Hubungan dengan pasien wajib dipilih.',
            'hubungan.in' =>
                'Pilihan hubungan dengan pasien tidak valid.',
        ];
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
