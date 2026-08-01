<?php

namespace Tests\Feature;

use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    public function test_public_pages_receive_global_security_headers(): void
    {
        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertHeader(
                'Referrer-Policy',
                'strict-origin-when-cross-origin'
            )
            ->assertHeader(
                'Permissions-Policy',
                'camera=(self), microphone=(), geolocation=(), payment=(), usb=()'
            );

        $this->assertStringContainsString(
            "frame-ancestors 'self'",
            (string) $response->headers->get(
                'Content-Security-Policy'
            )
        );
    }

    public function test_privacy_policy_page_is_available(): void
    {
        $this->get('/kebijakan-privasi')
            ->assertOk()
            ->assertSee('Kebijakan Privasi MD Farma')
            ->assertSee('Masa akses pasien dan arsip internal');
    }
}
