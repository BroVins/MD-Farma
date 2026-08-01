<?php

namespace App\Http\Controllers;

use App\Models\ConsultationHistoryOwner;
use App\Models\ConsultationPatientProfile;
use App\Support\PatientAccessCookie;
use App\Support\PatientHistoryAccess;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ConsultationPatientProfileController extends Controller
{
    public function index(
        Request $request,
        PatientAccessCookie $accessCookie,
        PatientHistoryAccess $historyAccess
    ): View|RedirectResponse {
        [$guest, $owner] = $this->resolveUnlockedOwner(
            $request,
            $accessCookie,
            $historyAccess
        );

        if (! $owner) {
            return redirect()->route('consultation.entry');
        }

        $accessCookie->refresh($request, $guest);

        $profiles = $owner
            ->patientProfiles()
            ->withCount('consultations')
            ->orderByDesc('is_default')
            ->orderByDesc('last_used_at')
            ->orderBy('name')
            ->get();

        return view('consultation.profiles', [
            'profiles' => $profiles,
            'relationshipOptions' =>
                ConsultationPatientProfile::relationshipOptions(),
        ]);
    }

    public function store(
        Request $request,
        PatientAccessCookie $accessCookie,
        PatientHistoryAccess $historyAccess
    ): RedirectResponse {
        [$guest, $owner] = $this->resolveUnlockedOwner(
            $request,
            $accessCookie,
            $historyAccess
        );

        abort_unless($owner, 404);

        $validated = $request->validate(
            $this->profileRules(),
            $this->profileMessages()
        );

        DB::transaction(function () use ($owner, $validated): void {
            $hasProfiles = $owner
                ->patientProfiles()
                ->lockForUpdate()
                ->first() !== null;

            if (! $hasProfiles || (bool) ($validated['is_default'] ?? false)) {
                $owner->patientProfiles()->update([
                    'is_default' => false,
                ]);
            }

            $owner->patientProfiles()->create([
                'name' => $validated['name'],
                'age' => $validated['age'],
                'phone' => $validated['phone'],
                'relationship' => $validated['relationship'],
                'is_default' => ! $hasProfiles
                    || (bool) ($validated['is_default'] ?? false),
            ]);
        });

        return redirect()
            ->route('consultation.profiles.index')
            ->with('status', 'Profil pasien berhasil ditambahkan.');
    }

    public function update(
        Request $request,
        ConsultationPatientProfile $profile,
        PatientAccessCookie $accessCookie,
        PatientHistoryAccess $historyAccess
    ): RedirectResponse {
        [, $owner] = $this->resolveUnlockedOwner(
            $request,
            $accessCookie,
            $historyAccess
        );

        $this->ensureOwnedBy($profile, $owner);

        $validated = $request->validate(
            $this->profileRules(),
            $this->profileMessages()
        );

        DB::transaction(function () use (
            $owner,
            $profile,
            $validated
        ): void {
            $requestedDefault = (bool) (
                $validated['is_default'] ?? false
            );

            if ($requestedDefault) {
                $owner->patientProfiles()
                    ->where('id', '!=', $profile->id)
                    ->update(['is_default' => false]);
            }

            $otherDefaultExists = $owner->patientProfiles()
                ->where('id', '!=', $profile->id)
                ->where('is_default', true)
                ->exists();

            $profile->update([
                'name' => $validated['name'],
                'age' => $validated['age'],
                'phone' => $validated['phone'],
                'relationship' => $validated['relationship'],
                'is_default' => $requestedDefault
                    || ($profile->is_default && ! $otherDefaultExists),
            ]);
        });

        return redirect()
            ->route('consultation.profiles.index')
            ->with(
                'status',
                'Profil pasien berhasil diperbarui. Data konsultasi lama tidak berubah.'
            );
    }

    public function makeDefault(
        Request $request,
        ConsultationPatientProfile $profile,
        PatientAccessCookie $accessCookie,
        PatientHistoryAccess $historyAccess
    ): RedirectResponse {
        [, $owner] = $this->resolveUnlockedOwner(
            $request,
            $accessCookie,
            $historyAccess
        );

        $this->ensureOwnedBy($profile, $owner);

        DB::transaction(function () use ($owner, $profile): void {
            $owner->patientProfiles()->update([
                'is_default' => false,
            ]);

            $profile->update([
                'is_default' => true,
            ]);
        });

        return redirect()
            ->route('consultation.profiles.index')
            ->with(
                'status',
                $profile->name.' sekarang menjadi profil utama.'
            );
    }

    private function resolveUnlockedOwner(
        Request $request,
        PatientAccessCookie $accessCookie,
        PatientHistoryAccess $historyAccess
    ): array {
        $guest = $accessCookie->restore($request);

        if (! $guest) {
            return [null, null];
        }

        $guest->loadMissing('historyOwner');
        $owner = $guest->historyOwner;

        if (
            ! $owner
            || ! $historyAccess->isUnlocked($request, $owner)
        ) {
            return [$guest, null];
        }

        return [$guest, $owner];
    }

    private function ensureOwnedBy(
        ConsultationPatientProfile $profile,
        ?ConsultationHistoryOwner $owner
    ): void {
        abort_unless(
            $owner
            && (int) $profile->history_owner_id
                === (int) $owner->id,
            404
        );
    }

    private function profileRules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:100',
            ],
            'age' => [
                'required',
                'integer',
                'min:1',
                'max:120',
            ],
            'phone' => [
                'required',
                'string',
                'max:25',
                'regex:/^[0-9+\-\s()]+$/',
            ],
            'relationship' => [
                'required',
                Rule::in(array_keys(
                    ConsultationPatientProfile::relationshipOptions()
                )),
            ],
            'is_default' => [
                'nullable',
                'boolean',
            ],
        ];
    }

    private function profileMessages(): array
    {
        return [
            'name.required' => 'Nama pasien wajib diisi.',
            'age.required' => 'Umur pasien wajib diisi.',
            'age.integer' => 'Umur pasien harus berupa angka.',
            'age.min' => 'Umur pasien minimal 1 tahun.',
            'age.max' => 'Umur pasien maksimal 120 tahun.',
            'phone.required' => 'Nomor HP wajib diisi.',
            'phone.regex' => 'Format nomor HP tidak sesuai.',
            'relationship.required' => 'Hubungan dengan pasien wajib dipilih.',
            'relationship.in' => 'Pilihan hubungan pasien tidak valid.',
        ];
    }
}
