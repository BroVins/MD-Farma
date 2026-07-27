<?php

namespace App\Http\Controllers;

use App\Events\AdminDashboardActivity;
use App\Events\AdminInboxActivity;
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
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class MessageController extends Controller
{
    private const ATTACHMENT_MAX_KB = 10240;

    private const ATTACHMENT_EXTENSIONS = [
        'jpg',
        'jpeg',
        'png',
        'webp',
        'pdf',
        'doc',
        'docx',
        'xls',
        'xlsx',
    ];

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

        $validated = $this->validateMessage($request);
        $attachmentPath = $this->storeAttachment(
            $request,
            $consultation
        );

        $message = DB::transaction(
            function () use (
                $request,
                $consultation,
                $validated,
                $attachmentPath
            ): Message {
                $message = $consultation
                    ->messages()
                    ->create([
                        'sender' => 'user',
                        'message' =>
                            $validated['message']
                            ?? null,
                        // Kolom lama bernama image, tetapi sekarang
                        // menyimpan gambar maupun dokumen.
                        'image' => $attachmentPath,
                    ]);

                $consultation->forceFill([
                    'last_message_at' =>
                        $message->created_at,
                    'last_message_sender' => 'user',
                ])->save();

                AnalyticsEvent::recordOnce(
                    $request,
                    'patient_message_sent',
                    $consultation,
                    [
                        'message_id' => $message->id,
                        'has_attachment' =>
                            $attachmentPath !== null,
                        'attachment_type' =>
                            $message->attachmentType(),
                    ],
                    'message:'.$message->id
                );

                return $message;
            }
        );

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

        $validated = $this->validateMessage($request);
        $attachmentPath = $this->storeAttachment(
            $request,
            $consultation
        );

        $message = DB::transaction(
            function () use (
                $request,
                $consultation,
                $validated,
                $attachmentPath
            ): Message {
                $message = $consultation
                    ->messages()
                    ->create([
                        'sender' => 'admin',
                        'message' =>
                            $validated['message']
                            ?? null,
                        'image' => $attachmentPath,
                    ]);

                $changes = [
                    'last_message_at' =>
                        $message->created_at,
                    'last_message_sender' => 'admin',
                ];

                if (! $consultation->first_admin_reply_at) {
                    $changes['first_admin_reply_at'] =
                        $message->created_at;
                }

                $consultation
                    ->forceFill($changes)
                    ->save();

                AnalyticsEvent::recordOnce(
                    $request,
                    'admin_replied',
                    $consultation,
                    [
                        'message_id' => $message->id,
                        'has_attachment' =>
                            $attachmentPath !== null,
                        'attachment_type' =>
                            $message->attachmentType(),
                    ],
                    'message:'.$message->id
                );

                return $message;
            }
        );

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
            $message->image,
            $message->attachmentName()
        );
    }

    private function validateMessage(Request $request): array
    {
        return $request->validate([
            'message' => [
                'nullable',
                'string',
                'max:2000',
                'required_without:image',
            ],
            'image' => [
                'nullable',
                'file',
                'mimes:'.implode(
                    ',',
                    self::ATTACHMENT_EXTENSIONS
                ),
                'max:'.self::ATTACHMENT_MAX_KB,
            ],
        ], [
            'message.required_without' =>
                'Tulis pesan atau pilih lampiran terlebih dahulu.',
            'image.file' =>
                'Lampiran yang dipilih tidak valid.',
            'image.mimes' =>
                'Lampiran harus berupa JPG, PNG, WebP, PDF, Word, atau Excel.',
            'image.max' =>
                'Ukuran lampiran maksimal 10 MB.',
        ]);
    }

    private function storeAttachment(
        Request $request,
        Consultation $consultation
    ): ?string {
        $file = $request->file('image');

        if (! $file) {
            return null;
        }

        $originalName = pathinfo(
            $file->getClientOriginalName(),
            PATHINFO_FILENAME
        );
        $extension = strtolower(
            $file->getClientOriginalExtension()
        );

        $safeBaseName = Str::slug(
            Str::ascii($originalName)
        );

        if ($safeBaseName === '') {
            $safeBaseName = 'lampiran';
        }

        $fileName = Str::uuid()
            .'_'
            .$safeBaseName
            .'.'
            .$extension;

        return $file->storeAs(
            'consultations/'.$consultation->public_id,
            $fileName,
            'local'
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

        $this->broadcastDashboardActivity(
            $consultation,
            $message
        );

        $this->broadcastInboxActivity(
            $consultation,
            $message
        );

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'realtime_delivered' => $broadcasted,
                'message' => $payload,
                'access_expires_at' =>
                    Auth::guard('patient')
                        ->user()
                        ?->expires_at
                        ?->toIso8601String(),
            ], 201);
        }

        return redirect()->route(
            'chat.show',
            $consultation
        );
    }

    private function broadcastInboxActivity(
        Consultation $consultation,
        Message $message
    ): void {
        try {
            event(
                new AdminInboxActivity(
                    $consultation->fresh([
                        'lastMessage',
                    ]),
                    $message->sender === 'user'
                        ? 'patient_message'
                        : 'admin_reply',
                    $message
                )
            );
        } catch (Throwable $exception) {
            Log::warning(
                'Sinkronisasi inbox realtime gagal.',
                [
                    'consultation_id' =>
                        $consultation->id,
                    'message_id' => $message->id,
                    'exception' => $exception::class,
                ]
            );
        }
    }

    private function broadcastDashboardActivity(
        Consultation $consultation,
        Message $message
    ): void {
        try {
            event(
                new AdminDashboardActivity(
                    $consultation->fresh(),
                    $message->sender === 'user'
                        ? 'patient_message'
                        : 'admin_reply',
                    $message
                )
            );
        } catch (Throwable $exception) {
            Log::warning(
                'Sinkronisasi aktivitas dashboard gagal.',
                [
                    'consultation_id' =>
                        $consultation->id,
                    'message_id' => $message->id,
                    'exception' => $exception::class,
                ]
            );
        }
    }
}
