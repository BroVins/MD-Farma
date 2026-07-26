<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MessageController extends Controller
{
    public function store(
        Request $request,
        Consultation $consultation
    ): RedirectResponse {
        $guest = Auth::guard('patient')->user();

        abort_unless(
            $guest
            && (int) $consultation->guest_id
                === (int) $guest->getAuthIdentifier(),
            404
        );

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
            ?->store(
                'consultations/'.$consultation->public_id,
                'local'
            );

        $consultation->messages()->create([
            'sender' => 'user',
            'message' => $validated['message'] ?? null,
            'image' => $imagePath,
        ]);

        return redirect()->route(
            'chat.show',
            $consultation
        );
    }

    public function reply(
        Request $request,
        Consultation $consultation
    ): RedirectResponse {
        $validated = $request->validate([
            'message' => [
                'required',
                'string',
                'max:2000',
            ],
        ]);

        $consultation->messages()->create([
            'sender' => 'admin',
            'message' => $validated['message'],
            'image' => null,
        ]);

        return redirect()->route(
            'chat.show',
            $consultation
        );
    }

    public function attachment(
        Consultation $consultation,
        Message $message
    ): StreamedResponse {
        abort_unless(
            (int) $message->consultation_id
                === (int) $consultation->id,
            404
        );

        abort_unless(
            $message->image
            && Storage::disk('local')
                ->exists($message->image),
            404
        );

        return Storage::disk('local')->response(
            $message->image
        );
    }
}
