<?php

namespace App\Http\Controllers;

use App\Models\ConsultationArchiveCopyRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AdminArchiveCopyRequestController extends Controller
{
    public function index(Request $request): View
    {
        $status = (string) $request->query('status', 'semua');

        if (! in_array(
            $status,
            array_merge(
                ['semua'],
                array_keys(ConsultationArchiveCopyRequest::STATUSES)
            ),
            true
        )) {
            $status = 'semua';
        }

        $search = trim((string) $request->query('q', ''));

        $requests = ConsultationArchiveCopyRequest::query()
            ->with([
                'consultation.patientProfile',
                'processedByAdmin',
            ])
            ->when(
                $status !== 'semua',
                fn ($query) => $query->where('status', $status)
            )
            ->when(
                $search !== '',
                function ($query) use ($search): void {
                    $query->where(function ($inner) use ($search): void {
                        $inner
                            ->where('public_id', 'like', "%{$search}%")
                            ->orWhere('contact_value', 'like', "%{$search}%")
                            ->orWhereHas(
                                'consultation',
                                function ($consultationQuery) use ($search): void {
                                    $consultationQuery
                                        ->where('nama', 'like', "%{$search}%")
                                        ->orWhere('no_hp', 'like', "%{$search}%");
                                }
                            );
                    });
                }
            )
            ->orderByRaw(
                "CASE status "
                ."WHEN 'pending' THEN 0 "
                ."WHEN 'verifying' THEN 1 "
                ."WHEN 'approved' THEN 2 "
                ."WHEN 'rejected' THEN 3 "
                ."ELSE 4 END"
            )
            ->orderByDesc('submitted_at')
            ->paginate(20)
            ->withQueryString();

        $counts = [
            'all' => ConsultationArchiveCopyRequest::count(),
        ];

        foreach (
            ConsultationArchiveCopyRequest::STATUSES
            as $key => $label
        ) {
            $counts[$key] = ConsultationArchiveCopyRequest::query()
                ->where('status', $key)
                ->count();
        }

        return view('admin.archive-requests.index', [
            'requests' => $requests,
            'selectedStatus' => $status,
            'search' => $search,
            'counts' => $counts,
            'statusOptions' =>
                ConsultationArchiveCopyRequest::STATUSES,
        ]);
    }

    public function show(
        ConsultationArchiveCopyRequest $archiveCopyRequest
    ): View {
        $archiveCopyRequest->load([
            'consultation.patientProfile',
            'consultation.lastMessage',
            'historyOwner',
            'patientProfile',
            'requestedByGuest',
            'processedByAdmin',
            'logs.admin',
        ]);

        return view('admin.archive-requests.show', [
            'archiveRequest' => $archiveCopyRequest,
            'allowedTransitions' => $this->allowedTransitions(
                $archiveCopyRequest->status
            ),
        ]);
    }

    public function update(
        Request $request,
        ConsultationArchiveCopyRequest $archiveCopyRequest
    ): RedirectResponse {
        $allowedTransitions = $this->allowedTransitions(
            $archiveCopyRequest->status
        );

        if ($allowedTransitions === []) {
            return back()->with(
                'warning',
                'Permintaan yang sudah selesai tidak dapat diubah lagi.'
            );
        }

        $validated = $request->validate([
            'status' => [
                'required',
                'in:'.implode(',', array_keys($allowedTransitions)),
            ],
            'decision_notes' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ], [
            'status.in' =>
                'Perubahan status tidak sesuai dengan alur verifikasi.',
            'decision_notes.max' =>
                'Catatan maksimal 2.000 karakter.',
        ]);

        $notes = trim((string) (
            $validated['decision_notes'] ?? ''
        ));

        if (
            in_array($validated['status'], ['rejected', 'completed'], true)
            && strlen($notes) < 10
        ) {
            throw ValidationException::withMessages([
                'decision_notes' =>
                    'Catatan minimal 10 karakter untuk penolakan atau penyelesaian permintaan.',
            ]);
        }

        DB::transaction(function () use (
            $archiveCopyRequest,
            $validated,
            $notes
        ): void {
            $lockedRequest = ConsultationArchiveCopyRequest::query()
                ->lockForUpdate()
                ->findOrFail($archiveCopyRequest->getKey());

            $transitions = $this->allowedTransitions(
                $lockedRequest->status
            );

            if (! array_key_exists($validated['status'], $transitions)) {
                throw ValidationException::withMessages([
                    'status' =>
                        'Status permintaan sudah berubah. Muat ulang halaman dan coba kembali.',
                ]);
            }

            $previousStatus = $lockedRequest->status;
            $newStatus = $validated['status'];
            $adminId = (int) Auth::guard('admin')->id();

            $changes = [
                'status' => $newStatus,
                'processed_by_admin_id' => $adminId,
                'decision_notes' => $notes !== '' ? $notes : null,
                'processed_at' => now(),
            ];

            if ($newStatus === 'completed') {
                $changes['completed_at'] = now();
            } elseif ($previousStatus === 'completed') {
                $changes['completed_at'] = null;
            }

            $lockedRequest->forceFill($changes)->save();

            $lockedRequest->logs()->create([
                'admin_id' => $adminId,
                'actor_type' => 'admin',
                'previous_status' => $previousStatus,
                'new_status' => $newStatus,
                'notes' => $notes !== '' ? $notes : null,
            ]);
        });

        return redirect()
            ->route(
                'admin.archive-requests.show',
                $archiveCopyRequest
            )
            ->with(
                'success',
                'Status permintaan salinan arsip berhasil diperbarui.'
            );
    }

    private function allowedTransitions(string $status): array
    {
        $labels = ConsultationArchiveCopyRequest::STATUSES;

        return match ($status) {
            'pending' => [
                'verifying' => $labels['verifying'],
                'rejected' => $labels['rejected'],
            ],
            'verifying' => [
                'approved' => $labels['approved'],
                'rejected' => $labels['rejected'],
            ],
            'approved' => [
                'completed' => $labels['completed'],
                'rejected' => $labels['rejected'],
            ],
            'rejected' => [
                'verifying' => $labels['verifying'],
            ],
            default => [],
        };
    }
}
