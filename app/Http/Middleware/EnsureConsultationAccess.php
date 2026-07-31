<?php

namespace App\Http\Middleware;

use App\Support\PatientAccessCookie;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureConsultationAccess
{
    public function __construct(
        private readonly PatientAccessCookie $accessCookie
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

        $allowed = $guest
            && $guest->expires_at
            && $guest->expires_at->isFuture()
            && (int) $consultation->guest_id
                === (int) $guest->getAuthIdentifier();

        abort_unless($allowed, 404);

        $this->accessCookie->refresh(
            $request,
            $guest
        );

        return $next($request);
    }
}
