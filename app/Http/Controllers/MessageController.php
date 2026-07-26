<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\AnalyticsEvent;
use App\Models\Consultation;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        abort_if(
            $consultation->status !== 'aktif',
            409,
            'Konsultasi sudah selesai.'
        );

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

        $imagePath = $request->file('image')?->store(
            'consultations/'.$consultation->public_id,
            'local'
        );

        $message = DB::transaction(function () use (
            $request,
            $consultation,
            $validated,
            $imagePath
        ): Message {
            $message = $consultation->messages()->create([
                'sender' => 'user',
                'message' => $validated['message'] ?? null,
                'image' => $imagePath,
            ]);

            $consultation->forceFill([
                'last_message_at' => $message->created_at,
            ])->save();

            AnalyticsEvent::recordOnce(
                $request,
                'patient_message_sent',
                $consultation,
                [
                    'message_id' => $message->id,
                    'has_attachment' => $imagePath !== null,
                ],
                'message:'.$message->id
            );

            return $message;
        });

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
        abort_if(
            $consultation->status !== 'aktif',
            409,
            'Aktifkan kembali konsultasi sebelum membalas.'
        );

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $message = DB::transaction(function () use (
            $request,
            $consultation,
            $validated
        ): Message {
            $message = $consultation->messages()->create([
                'sender' => 'admin',
                'message' => $validated['message'],
                'image' => null,
            ]);

            $changes = [
                'last_message_at' => $message->created_at,
            ];

            if (! $consultation->first_admin_reply_at) {
                $changes['first_admin_reply_at'] = $message->created_at;
            }

            $consultation->forceFill($changes)->save();

            AnalyticsEvent::recordOnce(
                $request,
                'admin_replied',
                $consultation,
                ['message_id' => $message->id],
                'message:'.$message->id
            );

            return $message;
        });

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
            (int) $message->consultation_id === (int) $consultation->id,
            404
        );

        abort_unless(
            $message->image
            && Storage::disk('local')->exists($message->image),
            404
        );

        return Storage::disk('local')->response($message->image);
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
                'access_expires_at' => Auth::guard('patient')
                    ->user()
                    ?->expires_at
                    ?->toIso8601String(),
            ], 201);
        }

        return redirect()->route('chat.show', $consultation);
    }
}
