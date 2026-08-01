<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplySecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        $response->headers->set(
            'X-Content-Type-Options',
            'nosniff'
        );
        $response->headers->set(
            'X-Frame-Options',
            'SAMEORIGIN'
        );
        $response->headers->set(
            'Referrer-Policy',
            'strict-origin-when-cross-origin'
        );
        $response->headers->set(
            'Permissions-Policy',
            'camera=(self), microphone=(), geolocation=(), payment=(), usb=()'
        );
        $response->headers->set(
            'X-Permitted-Cross-Domain-Policies',
            'none'
        );

        $contentSecurityPolicy = implode('; ', [
            "base-uri 'self'",
            "frame-ancestors 'self'",
            "object-src 'none'",
            "form-action 'self'",
        ]);

        if (
            app()->environment('production')
            && $request->isSecure()
        ) {
            $contentSecurityPolicy .= '; upgrade-insecure-requests';

            if ((bool) config('security.hsts_enabled', true)) {
                $response->headers->set(
                    'Strict-Transport-Security',
                    'max-age='.(int) config(
                        'security.hsts_max_age',
                        31536000
                    )
                );
            }
        }

        $response->headers->set(
            'Content-Security-Policy',
            $contentSecurityPolicy
        );

        return $response;
    }
}
