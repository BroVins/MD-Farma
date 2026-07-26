<?php

use App\Models\Admin;
use App\Models\Consultation;
use App\Models\ConsultationGuest;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel(
    'consultation.{publicId}',
    function (
        mixed $actor,
        string $publicId
    ): bool {
        $consultation = Consultation::query()
            ->select([
                'id',
                'guest_id',
                'public_id',
            ])
            ->where('public_id', $publicId)
            ->first();

        if (! $consultation) {
            return false;
        }

        if ($actor instanceof Admin) {
            return true;
        }

        return $actor instanceof ConsultationGuest
            && $actor->expires_at
            && $actor->expires_at->isFuture()
            && (int) $consultation->guest_id
                === (int) $actor
                    ->getAuthIdentifier();
    },
    [
        'guards' => [
            'admin',
            'patient',
        ],
    ]
);

Broadcast::channel(
    'admin.dashboard',
    fn (mixed $actor): bool =>
        $actor instanceof Admin,
    [
        'guards' => [
            'admin',
        ],
    ]
);

Broadcast::channel(
    'admin.inbox',
    fn (mixed $actor): bool =>
        $actor instanceof Admin,
    [
        'guards' => [
            'admin',
        ],
    ]
);
