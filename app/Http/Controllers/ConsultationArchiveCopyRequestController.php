<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\ConsultationArchiveCopyRequest;
use App\Support\PatientAccessCookie;
use App\Support\PatientHistoryAccess;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ConsultationArchiveCopyRequestController extends Controller
{
    public function store(
        Request $request,
        Consultation $consultation,
        PatientAccessCookie $accessCookie,
        PatientHistoryAccess $historyAccess
    ): RedirectResponse {
        $guest = $accessCookie->restore($request);

        if (! $guest) {
            return redirect()->route('consultation.create');
        }

        $accessCookie->refresh($request, $guest, true);
        $guest->loadMissing('historyOwner');
        $owner = $guest->historyOwner;

        if (
            ! $owner
            || ! $historyAccess->isUnlocked($request, $owner)
        ) {
            return redirect()
                ->route('consultation.entry')
                ->with(
                    'warning',
                    'Masukkan Password Riwayat sebelum mengajukan salinan arsip.'
                );
        }

        $ownedConsultation = $owner
            ->consultations()
            ->where(
                'consultations.id',
                $consultation->getKey()
            )
            ->first();

        abort_unless($ownedConsultation, 404);

        if (! $ownedConsultation->isPatientHistoryArchived()) {
            return redirect()
                ->route('consultation.history')
                ->with(
                    'warning',
                    'Permintaan salinan hanya tersedia untuk konsultasi yang sudah diarsipkan.'
                );
        }

        $validated = $request->validate([
            'consultation_public_id' => [
                'required',
                'string',
                Rule::in([$ownedConsultation->public_id]),
            ],
            'reason' => [
                'required',
                'string',
                'min:10',
                'max:1000',
            ],
            'contact_method' => [
                'required',
                'in:whatsapp,telepon,ambil_apotek',
            ],
            'contact_value' => [
                'required',
                'string',
                'min:8',
                'max:120',
                'regex:/^[0-9+\-\s()]+$/',
            ],
            'history_password' => [
                'required',
                'string',
                'max:128',
            ],
            'privacy_confirmation' => [
                'accepted',
            ],
        ], [
            'consultation_public_id.in' =>
                'Konsultasi yang dipilih tidak sesuai.',
            'reason.required' =>
                'Alasan permintaan wajib diisi.',
            'reason.min' =>
                'Jelaskan alasan permintaan minimal 10 karakter.',
            'contact_method.required' =>
                'Pilih metode tindak lanjut.',
            'contact_value.required' =>
                'Nomor kontak wajib diisi.',
            'contact_value.regex' =>
                'Format nomor kontak tidak sesuai.',
            'history_password.required' =>
                'Password Riwayat wajib diisi.',
            'privacy_confirmation.accepted' =>
                'Konfirmasi pengajuan wajib disetujui.',
        ]);

        if (! $historyAccess->verifyPassword(
            $owner,
            $validated['history_password']
        )) {
            throw ValidationException::withMessages([
                'history_password' =>
                    'Data pengajuan tidak dapat diverifikasi. Periksa kembali Password Riwayat Anda.',
            ]);
        }

        DB::transaction(function () use (
            $ownedConsultation,
            $owner,
            $guest,
            $validated
        ): void {
            $lockedConsultation = Consultation::query()
                ->lockForUpdate()
                ->findOrFail($ownedConsultation->getKey());

            if (! $lockedConsultation->isPatientHistoryArchived()) {
                throw ValidationException::withMessages([
                    'reason' =>
                        'Konsultasi ini belum memenuhi syarat pengajuan arsip.',
                ]);
            }

            $hasOpenRequest = ConsultationArchiveCopyRequest::query()
                ->where(
                    'consultation_id',
                    $lockedConsultation->getKey()
                )
                ->where(
                    'history_owner_id',
                    $owner->getKey()
                )
                ->whereIn(
                    'status',
                    ConsultationArchiveCopyRequest::ACTIVE_STATUSES
                )
                ->lockForUpdate()
                ->exists();

            if ($hasOpenRequest) {
                throw ValidationException::withMessages([
                    'reason' =>
                        'Permintaan untuk konsultasi ini masih diproses. Tunggu sampai proses sebelumnya selesai.',
                ]);
            }

            $archiveRequest = ConsultationArchiveCopyRequest::query()
                ->create([
                    'consultation_id' =>
                        $lockedConsultation->getKey(),
                    'history_owner_id' => $owner->getKey(),
                    'patient_profile_id' =>
                        $lockedConsultation->patient_profile_id,
                    'requested_by_guest_id' => $guest->getKey(),
                    'status' => 'pending',
                    'reason' => trim($validated['reason']),
                    'contact_method' =>
                        $validated['contact_method'],
                    'contact_value' =>
                        trim($validated['contact_value']),
                    'patient_confirmed_at' => now(),
                    'submitted_at' => now(),
                ]);

            $archiveRequest->logs()->create([
                'admin_id' => null,
                'actor_type' => 'patient',
                'previous_status' => null,
                'new_status' => 'pending',
                'notes' => 'Permintaan salinan arsip diajukan oleh pasien melalui dashboard.',
            ]);
        });

        return redirect()
            ->route('consultation.history', [
                'status' => 'selesai',
                'profil' => $request->query('profil', 'semua'),
            ])
            ->with(
                'status',
                'Permintaan salinan arsip berhasil dikirim. Tim MD Farma akan melakukan verifikasi terlebih dahulu.'
            );
    }
}
