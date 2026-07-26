<?php

namespace App\Http\Controllers;

use App\Models\Consultation;

class ChatController extends Controller
{
    public function index($id)
    {
        $consultation = Consultation::with('messages')
            ->findOrFail($id);

        return view('consultation.chat', compact('consultation'));
    }
}