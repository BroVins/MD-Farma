<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public Message $message
    ) {
        $this->message->loadMissing('consultation');
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel(
                'consultation.'.
                $this->message->consultation->public_id
            ),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    public function broadcastWith(): array
    {
        $attachmentRoute = $this->message->image
            ? route(
                'chat.attachment',
                [
                    'consultation' =>
                        $this->message->consultation,
                    'message' => $this->message,
                ],
                false
            )
            : null;

        return [
            'id' => $this->message->id,
            'consultation_public_id' =>
                $this->message
                    ->consultation
                    ->public_id,
            'sender' => $this->message->sender,
            'message' => $this->message->message,
            // Dipertahankan untuk kompatibilitas UI lama.
            'attachment_url' =>
                $this->message->isImageAttachment()
                    ? $attachmentRoute
                    : null,
            'attachment_download_url' => $attachmentRoute,
            'attachment_name' =>
                $this->message->attachmentName(),
            'attachment_type' =>
                $this->message->attachmentType(),
            'attachment_extension' =>
                $this->message->attachmentExtension(),
            'created_at' => $this->message
                ->created_at
                ?->toIso8601String(),
        ];
    }
}
