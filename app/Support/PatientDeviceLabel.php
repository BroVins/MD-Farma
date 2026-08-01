<?php

namespace App\Support;

use Illuminate\Http\Request;

class PatientDeviceLabel
{
    public function fromRequest(Request $request): string
    {
        $userAgent = strtolower(
            (string) $request->userAgent()
        );

        $browser = match (true) {
            str_contains($userAgent, 'edg/') => 'Microsoft Edge',
            str_contains($userAgent, 'opr/')
                || str_contains($userAgent, 'opera') => 'Opera',
            str_contains($userAgent, 'firefox/') => 'Firefox',
            str_contains($userAgent, 'chrome/')
                || str_contains($userAgent, 'crios/') => 'Chrome',
            str_contains($userAgent, 'safari/') => 'Safari',
            default => 'Browser',
        };

        $device = match (true) {
            str_contains($userAgent, 'iphone') => 'iPhone',
            str_contains($userAgent, 'ipad') => 'iPad',
            str_contains($userAgent, 'android') => 'Android',
            str_contains($userAgent, 'windows') => 'Windows',
            str_contains($userAgent, 'macintosh')
                || str_contains($userAgent, 'mac os') => 'Mac',
            str_contains($userAgent, 'linux') => 'Linux',
            default => 'perangkat',
        };

        return $device === 'perangkat'
            ? $browser.' di perangkat'
            : $browser.' di '.$device;
    }
}
