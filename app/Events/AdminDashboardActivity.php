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

class AdminDashboardActivity implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public Consultation $consultation,
        public string $activityType,
        public ?Message $message = null
    ) {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('admin.dashboard'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'dashboard.activity';
    }

    public function broadcastWith(): array
    {
        $timezone = config(
            'analytics.timezone',
            'Asia/Jakarta'
        );

        $moment = $this->message?->created_at
            ?? $this->consultation->updated_at
            ?? $this->consultation->created_at;

        $localTime = CarbonImmutable::instance($moment)
            ->setTimezone($timezone);

        $typeLabel =
            $this->consultation->jenis_konsultasi
                === 'resep'
                ? 'resep dokter'
                : 'non resep';

        [$title, $toastBody, $browserBody, $notify] =
            match ($this->activityType) {
                'consultation_created' => [
                    'Konsultasi baru',
                    $this->consultation->nama
                        .' membuat konsultasi '
                        .$typeLabel.'.',
                    'Konsultasi baru masuk. '
                        .'Buka dashboard untuk melihat detail.',
                    true,
                ],
                'patient_message' => [
                    'Pesan pasien baru',
                    'Pesan baru dari '
                        .$this->consultation->nama.'.',
                    'Pesan pasien baru masuk.',
                    true,
                ],
                'status_changed' => [
                    'Status konsultasi diperbarui',
                    'Status konsultasi '
                        .$this->consultation->nama
                        .' menjadi '
                        .$this->consultation->status.'.',
                    'Status konsultasi diperbarui.',
                    false,
                ],
                default => [
                    'Dashboard diperbarui',
                    'Data konsultasi telah diperbarui.',
                    'Data dashboard diperbarui.',
                    false,
                ],
            };

        return [
            'activity_type' => $this->activityType,
            'notification' => [
                'title' => $title,
                'toast_body' => $toastBody,
                'browser_body' => $browserBody,
                'should_notify' => $notify,
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
                'created_at' =>
                    $this->consultation
                        ->created_at
                        ?->toIso8601String(),
                'last_message_at' =>
                    $this->consultation
                        ->last_message_at
                        ?->toIso8601String(),
                'chat_url' => route(
                    'chat.show',
                    $this->consultation,
                    false
                ),
            ],
            'message' => $this->message
                ? [
                    'id' => $this->message->id,
                    'sender' => $this->message->sender,
                    'has_attachment' =>
                        $this->message->image !== null,
                ]
                : null,
            'occurred_at' => $localTime
                ->toIso8601String(),
            'occurred_at_label' => $localTime
                ->locale('id')
                ->isoFormat(
                    'dddd, D MMMM YYYY [pukul] HH.mm'
                ).' WIB',
        ];
    }
}
