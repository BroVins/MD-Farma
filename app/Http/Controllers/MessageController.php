<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(
        Request $request,
        int $id
    ): RedirectResponse {
        Consultation::findOrFail($id);

        $validated = $request->validate([
            'message' => [
                'nullable',
                'string',
                'max:2000',
                'required_without:image',
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        $imagePath = $request
            ->file('image')
            ?->store('chat-images', 'public');

        Message::create([
            'consultation_id' => $id,
            'sender' => 'user',
            'message' => $validated['message'] ?? null,
            'image' => $imagePath,
        ]);

        return redirect()->route('chat.show', $id);
    }

    public function reply(
        Request $request,
        int $id
    ): RedirectResponse {
        Consultation::findOrFail($id);

        $validated = $request->validate([
            'message' => [
                'required',
                'string',
                'max:2000',
            ],
        ]);

        Message::create([
            'consultation_id' => $id,
            'sender' => 'admin',
            'message' => $validated['message'],
            'image' => null,
        ]);

        return redirect()->route('chat.show', $id);
    }
}
