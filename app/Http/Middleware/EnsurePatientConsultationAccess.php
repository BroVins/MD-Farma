<?php

namespace App\Http\Middleware;

use App\Support\PatientAccessCookie;
use App\Support\PatientHistoryAccess;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePatientConsultationAccess
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
                    'Masukkan Password Riwayat untuk membuka konsultasi.'
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
                    'Riwayat konsultasi tersebut telah melewati masa akses pasien dan sekarang berada dalam arsip internal MD Farma.'
                );
        }

        /*
         * Polling chat.messages sengaja tidak memperpanjang masa perangkat.
         * Hanya membuka chat atau mengirim pesan yang dianggap aktivitas nyata.
         */
        if ($request->routeIs('chat.show', 'chat.send')) {
            $this->accessCookie->refresh(
                $request,
                $guest
            );
        }

        return $next($request);
    }
}
