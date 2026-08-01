<?php

namespace App\Events;

use App\Models\Consultation;
use App\Models\Message;
use Carbon\CarbonImmutable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class AdminInboxActivity implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public Consultation $consultation,
        public string $activityType,
        public ?Message $message = null
    ) {
        $this->consultation->loadMissing(
            'lastMessage'
        );
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('admin.inbox'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'inbox.activity';
    }

    public function broadcastWith(): array
    {
        $timezone = config(
            'analytics.timezone',
            'Asia/Jakarta'
        );

        $lastMessage = $this->message
            ?? $this->consultation->lastMessage;

        $moment = $lastMessage?->created_at
            ?? $this->consultation->updated_at
            ?? $this->consultation->created_at;

        $localTime = CarbonImmutable::instance(
            $moment
        )->setTimezone($timezone);

        [$title, $body, $shouldNotify] = match (
            $this->activityType
        ) {
            'consultation_created' => [
                'Konsultasi baru',
                'Ada pasien yang baru saja membuat konsultasi.',
                true,
            ],
            'patient_message' => [
                'Pesan pasien baru',
                'Ada pesan pasien baru yang perlu ditinjau.',
                true,
            ],
            'admin_reply' => [
                'Balasan terkirim',
                'Balasan untuk '
                    .$this->consultation->nama
                    .' berhasil dikirim.',
                false,
            ],
            'status_changed' => [
                'Status diperbarui',
                'Konsultasi '
                    .$this->consultation->nama
                    .' menjadi '
                    .$this->consultation->status.'.',
                false,
            ],
            default => [
                'Inbox diperbarui',
                'Data percakapan telah diperbarui.',
                false,
            ],
        };

        $preview = $lastMessage?->message
            ? Str::limit(
                preg_replace(
                    '/\s+/',
                    ' ',
                    trim($lastMessage->message)
                ),
                100
            )
            : ($lastMessage?->image
                ? 'Lampiran gambar'
                : 'Belum ada pesan');

        return [
            'activity_type' => $this->activityType,
            'notification' => [
                'title' => $title,
                'body' => $body,
                'should_notify' => $shouldNotify,
            ],
            'consultation' => [
                'public_id' =>
                    $this->consultation->public_id,
                'nama' => $this->consultation->nama,
                'jenis_konsultasi' =>
                    $this->consultation
                        ->jenis_konsultasi,
                'status' =>
                    $this->consultation->status,
                'last_message_sender' =>
                    $this->consultation
                        ->last_message_sender,
                'last_message_at' =>
                    $this->consultation
                        ->last_message_at
                        ?->toIso8601String(),
                'preview' => $preview,
                'inbox_url' => route(
                    'admin.inbox.show',
                    $this->consultation,
                    false
                ),
            ],
            'message' => $lastMessage
                ? [
                    'id' => $lastMessage->id,
                    'sender' => $lastMessage->sender,
                    'created_at' =>
                        $lastMessage->created_at
                            ?->toIso8601String(),
                    'has_attachment' =>
                        $lastMessage->image !== null,
                ]
                : null,
            'occurred_at' =>
                $localTime->toIso8601String(),
            'occurred_at_label' =>
                $localTime
                    ->locale('id')
                    ->isoFormat(
                        'dddd, D MMMM YYYY [pukul] HH.mm'
                    ).' WIB',
        ];
    }
}
