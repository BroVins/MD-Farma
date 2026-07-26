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
        return [
            'id' => $this->message->id,
            'sender' => $this->message->sender,
            'message' => $this->message->message,
            'attachment_url' => $this->message->image
                ? route(
                    'chat.attachment',
                    [
                        'consultation' =>
                            $this->message->consultation,
                        'message' => $this->message,
                    ],
                    false
                )
                : null,
            'created_at' => $this->message
                ->created_at
                ?->toIso8601String(),
        ];
    }
}
