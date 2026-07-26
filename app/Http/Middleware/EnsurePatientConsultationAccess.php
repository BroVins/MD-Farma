<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePatientConsultationAccess
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $consultation = $request->route('consultation');
        $guest = Auth::guard('patient')->user();

        $allowed = $guest
            && $guest->expires_at
            && $guest->expires_at->isFuture()
            && (int) $consultation->guest_id
                === (int) $guest->getAuthIdentifier();

        abort_unless($allowed, 404);

        $guest->forceFill([
            'expires_at' => now()->addHours(2),
        ])->save();

        return $next($request);
    }
}
