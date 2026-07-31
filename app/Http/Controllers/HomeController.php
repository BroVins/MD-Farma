<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home');
    }

    public function profile(): View
    {
        return view('profile');
    }

    public function partnership(): View
    {
        $number = preg_replace(
            '/\D+/',
            '',
            (string) config('mdfarma.whatsapp_number')
        ) ?? '';

        $message = trim(
            (string) config('mdfarma.whatsapp_message')
        );

        $isConfigured = str_starts_with($number, '62')
            && strlen($number) >= 10
            && strlen($number) <= 15;

        $whatsappUrl = $isConfigured
            ? 'https://wa.me/' . $number
                . ($message !== ''
                    ? '?text=' . rawurlencode($message)
                    : '')
            : null;

        $displayNumber = $isConfigured
            ? '+' . $number
            : null;

        return view('partnership', compact(
            'isConfigured',
            'whatsappUrl',
            'displayNumber'
        ));
    }
}
