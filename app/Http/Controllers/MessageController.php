<?php

namespace App\Http\Controllers;

use App\Events\AdminDashboardActivity;
use App\Events\AdminInboxActivity;
use App\Events\MessageSent;
use App\Models\AnalyticsEvent;
use App\Models\Consultation;
use App\Models\Message;
use App\Support\PatientConsultationAccess;
use App\Support\ConsultationAudit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class MessageController extends Controller
{
    private const IMAGE_MAX_KB = 5120;

    private const PDF_MAX_KB = 10240;

    private const ATTACHMENT_MIME_EXTENSIONS = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'application/pdf' => 'pdf',
    ];

    public function index(
        Request $request,
        Consultation $consultation
    ): JsonResponse {
        $validated = $request->validate([
            'after_id' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ]);

        $afterId = (int) (
            $validated['after_id']
            ?? 0
        );

        $messages = $consultation
            ->messages()
            ->with('classificationNotice')
            ->where('id', '>', $afterId)
            ->oldest('id')
            ->limit(200)
            ->get();

        $payloads = $messages
            ->map(function (Message $message) use (
                $consultation
            ): array {
                $message->setRelation(
                    'consultation',
                    $consultation
                );

                return (new MessageSent($message))
                    ->broadcastWith();
            })
            ->values();

        $lastMessageId = $messages
            ->last()
            ?->id
            ?? $afterId;

        return response()->json([
            'messages' => $payloads,
            'last_message_id' => $lastMessageId,
            'consultation_status' =>
                $consultation->status,
            'access_expires_at' =>
                $request->routeIs('chat.messages')
                    ? Auth::guard('patient')
                        ->user()
                        ?->expires_at
                        ?->toIso8601String()
                    : null,
        ]);
    }

    public function store(
        Request $request,
        Consultation $consultation,
        PatientConsultationAccess $consultationAccess
    ): JsonResponse|RedirectResponse {
        abort_if(
            $consultation->status !== 'aktif',
            409,
            'Konsultasi sudah selesai.'
        );

        $guest = Auth::guard('patient')->user();

        abort_unless(
            $guest
            && $consultationAccess->owns(
                $guest,
                $consultation
            ),
            404
        );

        $validated = $this->validateMessage($request);
        $attachmentPath = $this->storeAttachment(
            $request,
            $consultation
        );

        try {
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
        } catch (Throwable $exception) {
            $this->deleteAttachmentIfStored($attachmentPath);

            throw $exception;
        }

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

        try {
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
        } catch (Throwable $exception) {
            $this->deleteAttachmentIfStored($attachmentPath);

            throw $exception;
        }

        return $this->broadcastAndRespond(
            $request,
            $consultation,
            $message
        );
    }

    public function attachment(
        Request $request,
        Consultation $consultation,
        Message $message,
        ConsultationAudit $audit
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

        $audit->recordAccess(
            $request,
            'attachment_opened',
            $consultation,
            $message,
            metadata: [
                'attachment_type' => $message->attachmentType(),
                'attachment_extension' => $message->attachmentExtension(),
            ]
        );

        $response = $message->attachmentType() === 'document'
            ? Storage::disk('local')->download(
                $message->image,
                $message->attachmentName()
            )
            : Storage::disk('local')->response(
                $message->image,
                $message->attachmentName()
            );

        $response->headers->set(
            'X-Content-Type-Options',
            'nosniff'
        );
        $response->headers->set(
            'Cache-Control',
            'private, no-store, max-age=0'
        );

        return $response;
    }

    private function validateMessage(Request $request): array
    {
        $validated = $request->validate([
            'message' => [
                'nullable',
                'string',
                'max:2000',
                'required_without:image',
            ],
            'image' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp,pdf',
                'mimetypes:image/jpeg,image/png,image/webp,application/pdf',
                'max:'.self::PDF_MAX_KB,
            ],
        ], [
            'message.required_without' =>
                'Tulis pesan atau pilih lampiran terlebih dahulu.',
            'image.file' =>
                'Lampiran yang dipilih tidak valid.',
            'image.mimes' =>
                'Lampiran hanya boleh berupa JPG, PNG, WebP, atau PDF.',
            'image.mimetypes' =>
                'Isi file tidak sesuai. Gunakan gambar JPG, PNG, WebP, atau PDF yang valid.',
            'image.max' =>
                'Ukuran dokumen PDF maksimal 10 MB.',
        ]);

        $file = $request->file('image');

        if (! $file) {
            return $validated;
        }

        $mimeType = (string) $file->getMimeType();

        if (! array_key_exists(
            $mimeType,
            self::ATTACHMENT_MIME_EXTENSIONS
        )) {
            throw ValidationException::withMessages([
                'image' =>
                    'Lampiran hanya boleh berupa JPG, PNG, WebP, atau PDF yang valid.',
            ]);
        }

        if (
            str_starts_with($mimeType, 'image/')
            && $file->getSize() > self::IMAGE_MAX_KB * 1024
        ) {
            throw ValidationException::withMessages([
                'image' => 'Ukuran gambar maksimal 5 MB.',
            ]);
        }

        return $validated;
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
        $mimeType = (string) $file->getMimeType();
        $extension = self::ATTACHMENT_MIME_EXTENSIONS[
            $mimeType
        ] ?? null;

        if (! $extension) {
            throw ValidationException::withMessages([
                'image' =>
                    'Lampiran hanya boleh berupa JPG, PNG, WebP, atau PDF yang valid.',
            ]);
        }

        $safeBaseName = Str::slug(
            Str::ascii($originalName)
        );

        if ($safeBaseName === '') {
            $safeBaseName = 'lampiran';
        }

        $safeBaseName = mb_substr($safeBaseName, 0, 80);

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

    private function deleteAttachmentIfStored(
        ?string $attachmentPath
    ): void {
        if (! $attachmentPath) {
            return;
        }

        try {
            Storage::disk('local')->delete($attachmentPath);
        } catch (Throwable $exception) {
            Log::warning(
                'Lampiran gagal dibersihkan setelah transaksi pesan gagal.',
                [
                    'path' => $attachmentPath,
                    'exception' => $exception::class,
                ]
            );
        }
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

        if ($request->routeIs('admin.chat.reply')) {
            return redirect()->route(
                'admin.inbox.show',
                $consultation
            );
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
