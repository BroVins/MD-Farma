<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function partnership()
    {
        $number = preg_replace(
            '/\D+/',
            '',
            (string) config('mdfarma.whatsapp_number')
        );

        $message = trim(
            (string) config('mdfarma.whatsapp_message')
        );

        $isConfigured = is_string($number)
            && str_starts_with($number, '62')
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
            'number',
            'message',
            'isConfigured',
            'whatsappUrl',
            'displayNumber'
        ));
    }
}
