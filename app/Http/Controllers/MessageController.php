<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Consultation;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class MessageController extends Controller
{
    public function store(
        Request $request,
        Consultation $consultation
    ): JsonResponse|RedirectResponse {
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

        $message = $consultation->messages()->create([
            'sender' => 'user',
            'message' => $validated['message'] ?? null,
            'image' => $imagePath,
        ]);

        return $this->broadcastAndRespond(
            $request,
            $consultation,
            $message
        );
    }

    public function reply(
        Request $request,
        Consultation $consultation
    ): JsonResponse|RedirectResponse {
        $validated = $request->validate([
            'message' => [
                'required',
                'string',
                'max:2000',
            ],
        ]);

        $message = $consultation->messages()->create([
            'sender' => 'admin',
            'message' => $validated['message'],
            'image' => null,
        ]);

        return $this->broadcastAndRespond(
            $request,
            $consultation,
            $message
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

    private function broadcastAndRespond(
        Request $request,
        Consultation $consultation,
        Message $message
    ): JsonResponse|RedirectResponse {
        $message->loadMissing('consultation');

        $event = new MessageSent($message);
        $payload = $event->broadcastWith();
        $broadcasted = true;

        try {
            event($event);
        } catch (Throwable $exception) {
            $broadcasted = false;

            Log::warning(
                'Pesan tersimpan, tetapi broadcast realtime gagal.',
                [
                    'message_id' => $message->id,
                    'exception' => $exception::class,
                ]
            );
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'realtime_delivered' => $broadcasted,
                'message' => $payload,
                'access_expires_at' =>
                    Auth::guard('patient')->user()
                        ?->expires_at
                        ?->toIso8601String(),
            ], 201);
        }

        return redirect()->route(
            'chat.show',
            $consultation
        );
    }
}
