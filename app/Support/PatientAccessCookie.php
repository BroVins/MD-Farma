<?php

namespace App\Support;

use App\Models\ConsultationGuest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Cookie as HttpCookie;

class PatientAccessCookie
{
    private const TOKEN_BYTES = 32;

    private const REQUEST_TOKEN_ATTRIBUTE =
        'md_farma_patient_device_token';

    public function make(
        Request $request,
        ConsultationGuest $guest
    ): HttpCookie {
        $this->rememberDeviceMetadata($request, $guest);

        $token = $this->tokenFromRequest($request);

        if (! $this->tokenMatchesGuest($token, $guest)) {
            $token = $this->issueToken($guest);
            $this->rememberToken($request, $token);
        }

        if (! $guest->expires_at || $guest->expires_at->isPast()) {
            $guest->forceFill([
                'expires_at' => $this->expiresAt(),
                'last_seen_at' => now(),
                'revoked_at' => null,
            ])->save();
        }

        return $this->cookieForToken(
            $request,
            $token,
            $guest->expires_at
        );
    }

    public function restore(
        Request $request
    ): ?ConsultationGuest {
        $guest = Auth::guard('patient')->user();

        if ($guest instanceof ConsultationGuest) {
            if (! $this->isActive($guest)) {
                Auth::guard('patient')->logout();

                return null;
            }

            $this->rememberDeviceMetadata($request, $guest);

            $token = $this->tokenFromRequest($request);

            if (! $this->tokenMatchesGuest($token, $guest)) {
                $token = $this->issueToken($guest);
                $this->rememberToken($request, $token);
                Cookie::queue(
                    $this->cookieForToken(
                        $request,
                        $token,
                        $guest->expires_at
                    )
                );
            }

            return $guest;
        }

        $cookieValue = $this->tokenFromRequest($request);

        if (! is_string($cookieValue) || $cookieValue === '') {
            return null;
        }

        $guest = $this->findByDeviceToken($cookieValue);

        if (! $guest && Str::isUuid($cookieValue)) {
            $guest = ConsultationGuest::query()
                ->where('public_id', $cookieValue)
                ->whereNull('revoked_at')
                ->where('expires_at', '>', now())
                ->first();

            if ($guest) {
                $newToken = $this->issueToken($guest);
                $this->rememberToken($request, $newToken);
                Cookie::queue(
                    $this->cookieForToken(
                        $request,
                        $newToken,
                        $guest->expires_at
                    )
                );
            }
        }

        if (! $guest || ! $this->isActive($guest)) {
            return null;
        }

        $this->rememberDeviceMetadata($request, $guest);
        Auth::guard('patient')->login($guest);

        return $guest;
    }

    public function refresh(
        Request $request,
        ConsultationGuest $guest,
        bool $force = false
    ): void {
        if (! $this->isActive($guest)) {
            return;
        }

        $this->rememberDeviceMetadata($request, $guest);

        $now = now();
        $writeIntervalHours = max(
            1,
            (int) config(
                'consultation.patient_device_seen_interval_hours',
                6
            )
        );
        $refreshWindowDays = max(
            1,
            (int) config(
                'consultation.patient_device_refresh_window_days',
                30
            )
        );

        $shouldTouch = $force
            || ! $guest->last_seen_at
            || $guest->last_seen_at->lte(
                $now->copy()->subHours($writeIntervalHours)
            );

        $shouldExtend = $force
            || ! $guest->expires_at
            || $guest->expires_at->lte(
                $now->copy()->addDays($refreshWindowDays)
            );

        $token = $this->tokenFromRequest($request);
        $hasValidToken = $this->tokenMatchesGuest(
            $token,
            $guest
        );

        if (! $hasValidToken) {
            $token = $this->issueToken($guest);
            $this->rememberToken($request, $token);
            $hasValidToken = true;
            $shouldTouch = true;
        }

        if ($shouldTouch || $shouldExtend) {
            $changes = [];

            if ($shouldTouch) {
                $changes['last_seen_at'] = $now;
            }

            if ($shouldExtend) {
                $changes['expires_at'] = $this->expiresAt();
            }

            if ($changes !== []) {
                $guest->forceFill($changes)->save();
            }
        }

        if ($hasValidToken && ($shouldExtend || $force)) {
            Cookie::queue(
                $this->cookieForToken(
                    $request,
                    $token,
                    $guest->expires_at
                )
            );
        }
    }

    public function expiresAt(): Carbon
    {
        return now()->addDays(
            max(
                1,
                (int) config(
                    'consultation.patient_device_days',
                    90
                )
            )
        );
    }

    public function isActive(
        ConsultationGuest $guest
    ): bool {
        return ! $guest->revoked_at
            && $guest->expires_at
            && $guest->expires_at->isFuture();
    }


    private function rememberDeviceMetadata(
        Request $request,
        ConsultationGuest $guest
    ): void {
        $changes = [];

        if (! is_string($guest->device_label)
            || trim($guest->device_label) === '') {
            $changes['device_label'] = (new PatientDeviceLabel())
                ->fromRequest($request);
        }

        if (! $guest->first_seen_at) {
            $changes['first_seen_at'] = $guest->created_at
                ?? now();
        }

        if ($changes !== []) {
            $guest->forceFill($changes)->save();
        }
    }

    private function findByDeviceToken(
        string $token
    ): ?ConsultationGuest {
        if (! preg_match('/^[a-f0-9]{64}$/', $token)) {
            return null;
        }

        return ConsultationGuest::query()
            ->where('access_token_hash', $this->hashToken($token))
            ->whereNull('revoked_at')
            ->where('expires_at', '>', now())
            ->first();
    }

    private function issueToken(
        ConsultationGuest $guest
    ): string {
        $token = bin2hex(random_bytes(self::TOKEN_BYTES));

        $changes = [
            'access_token_hash' => $this->hashToken($token),
            'revoked_at' => null,
        ];

        if (! $guest->expires_at || $guest->expires_at->isPast()) {
            $changes['expires_at'] = $this->expiresAt();
        }

        if (! $guest->last_seen_at) {
            $changes['last_seen_at'] = now();
        }

        $guest->forceFill($changes)->save();

        return $token;
    }

    private function tokenMatchesGuest(
        mixed $token,
        ConsultationGuest $guest
    ): bool {
        return is_string($token)
            && preg_match('/^[a-f0-9]{64}$/', $token) === 1
            && is_string($guest->access_token_hash)
            && hash_equals(
                $guest->access_token_hash,
                $this->hashToken($token)
            );
    }

    private function tokenFromRequest(
        Request $request
    ): mixed {
        $remembered = $request->attributes->get(
            self::REQUEST_TOKEN_ATTRIBUTE
        );

        if (is_string($remembered) && $remembered !== '') {
            return $remembered;
        }

        return $request->cookie(
            (string) config(
                'consultation.patient_cookie',
                'md_farma_patient_access'
            )
        );
    }

    private function rememberToken(
        Request $request,
        string $token
    ): void {
        $request->attributes->set(
            self::REQUEST_TOKEN_ATTRIBUTE,
            $token
        );
    }

    private function hashToken(string $token): string
    {
        return hash('sha256', $token);
    }

    private function cookieForToken(
        Request $request,
        string $token,
        Carbon $expiresAt
    ): HttpCookie {
        $configuredSecure = config('session.secure');
        $secure = is_bool($configuredSecure)
            ? $configuredSecure
            : $request->isSecure();

        $minutes = max(
            1,
            now()->diffInMinutes($expiresAt, false)
        );

        return Cookie::make(
            name: (string) config(
                'consultation.patient_cookie',
                'md_farma_patient_access'
            ),
            value: $token,
            minutes: $minutes,
            path: '/',
            domain: config('session.domain'),
            secure: $secure,
            httpOnly: true,
            raw: false,
            sameSite: 'lax'
        );
    }
}
