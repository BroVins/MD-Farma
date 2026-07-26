<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use Illuminate\Contracts\View\View;

class ChatController extends Controller
{
    public function index(
        Consultation $consultation
    ): View {
        $consultation->load([
            'messages' => fn ($query) =>
                $query->oldest(),
        ]);

        return view(
            'consultation.chat',
            compact('consultation')
        );
    }
}
