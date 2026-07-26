<?php

namespace App\Http\Controllers;

use App\Models\AnalyticsEvent;
use App\Models\Consultation;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index(
        Request $request,
        Consultation $consultation
    ): View {
        $consultation->load([
            'messages' => fn ($query) => $query->oldest(),
        ]);

        if (
            Auth::guard('patient')->check()
            && ! Auth::guard('admin')->check()
        ) {
            AnalyticsEvent::recordOnce(
                $request,
                'chat_opened',
                $consultation,
                ['actor' => 'patient']
            );
        }

        return view(
            'consultation.chat',
            compact('consultation')
        );
    }
}
