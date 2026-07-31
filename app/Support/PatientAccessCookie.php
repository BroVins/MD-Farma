<?php

namespace App\Support;

use App\Models\ConsultationGuest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Cookie as HttpCookie;

class PatientAccessCookie
{
    public function make(
        Request $request,
        ConsultationGuest $guest
    ): HttpCookie {
        $hours = max(
            1,
            (int) config(
                'consultation.patient_access_hours',
                24
            )
        );

        $configuredSecure = config('session.secure');
        $secure = is_bool($configuredSecure)
            ? $configuredSecure
            : $request->isSecure();

        return Cookie::make(
            name: (string) config(
                'consultation.patient_cookie',
                'md_farma_patient_access'
            ),
            value: $guest->public_id,
            minutes: $hours * 60,
            path: '/',
            domain: config('session.domain'),
            secure: $secure,
            httpOnly: true,
            raw: false,
            sameSite: 'lax'
        );
    }

    public function restore(
        Request $request
    ): ?ConsultationGuest {
        $guest = Auth::guard('patient')->user();

        if (
            $guest instanceof ConsultationGuest
            && $guest->expires_at
            && $guest->expires_at->isFuture()
        ) {
            return $guest;
        }

        $publicId = $request->cookie(
            (string) config(
                'consultation.patient_cookie',
                'md_farma_patient_access'
            )
        );

        if (
            ! is_string($publicId)
            || ! Str::isUuid($publicId)
        ) {
            return null;
        }

        $guest = ConsultationGuest::query()
            ->where('public_id', $publicId)
            ->where('expires_at', '>', now())
            ->first();

        if (! $guest) {
            return null;
        }

        Auth::guard('patient')->login($guest);

        return $guest;
    }

    public function refresh(
        Request $request,
        ConsultationGuest $guest
    ): void {
        $hours = max(
            1,
            (int) config(
                'consultation.patient_access_hours',
                24
            )
        );

        $guest->forceFill([
            'expires_at' => now()->addHours($hours),
        ])->save();

        Cookie::queue(
            $this->make($request, $guest)
        );
    }
}
