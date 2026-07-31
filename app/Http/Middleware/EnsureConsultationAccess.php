<?php

namespace App\Http\Middleware;

use App\Support\PatientAccessCookie;
use App\Support\PatientHistoryAccess;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureConsultationAccess
{
    public function __construct(
        private readonly PatientAccessCookie $accessCookie,
        private readonly PatientHistoryAccess $historyAccess
    ) {
    }

    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $consultation = $request->route('consultation');

        if (Auth::guard('admin')->check()) {
            return $next($request);
        }

        $guest = $this->accessCookie->restore($request);

        if ($guest) {
            $consultation->loadMissing(
                'guest:id,history_owner_id'
            );
        }

        $sameDevice = $guest
            && (int) $consultation->guest_id
                === (int) $guest->getAuthIdentifier();

        $sameHistoryOwner = $guest
            && $guest->history_owner_id
            && $consultation->guest?->history_owner_id
            && (int) $guest->history_owner_id
                === (int) $consultation
                    ->guest
                    ->history_owner_id;

        $allowed = $guest
            && $this->accessCookie->isActive($guest)
            && ($sameDevice || $sameHistoryOwner);

        abort_unless($allowed, 404);

        $guest->loadMissing('historyOwner');

        if (
            $guest->historyOwner
            && ! $this->historyAccess->isUnlocked(
                $request,
                $guest->historyOwner
            )
        ) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' =>
                        'Riwayat konsultasi terkunci.',
                    'redirect' => route(
                        'consultation.entry'
                    ),
                ], 423);
            }

            return redirect()
                ->route('consultation.entry')
                ->with(
                    'warning',
                    'Masukkan Password Riwayat untuk membuka lampiran konsultasi.'
                );
        }

        if ($consultation->isPatientHistoryArchived()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Riwayat konsultasi ini telah diarsipkan dan tidak lagi tersedia pada dashboard pasien.',
                    'redirect' => route(
                        'consultation.history'
                    ),
                ], 410);
            }

            return redirect()
                ->route('consultation.history')
                ->with(
                    'warning',
                    'Lampiran konsultasi tersebut tidak lagi tersedia dari sisi pasien karena riwayat sudah diarsipkan.'
                );
        }

        $this->accessCookie->refresh(
            $request,
            $guest
        );

        return $next($request);
    }
}
