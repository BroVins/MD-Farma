<?php

use App\Models\Admin;
use App\Models\Consultation;
use App\Models\ConsultationGuest;
use App\Support\PatientHistoryAccess;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel(
    'consultation.{publicId}',
    function (
        mixed $actor,
        string $publicId
    ): bool {
        $consultation = Consultation::query()
            ->with('guest:id,history_owner_id')
            ->select([
                'id',
                'guest_id',
                'public_id',
                'status',
                'closed_at',
                'updated_at',
                'last_message_at',
            ])
            ->where('public_id', $publicId)
            ->first();

        if (! $consultation) {
            return false;
        }

        if ($actor instanceof Admin) {
            return true;
        }

        if (! $actor instanceof ConsultationGuest) {
            return false;
        }

        if (
            $actor->revoked_at
            || ! $actor->expires_at
            || $actor->expires_at->isPast()
        ) {
            return false;
        }

        $sameDevice = (int) $consultation->guest_id
            === (int) $actor->getAuthIdentifier();

        $sameHistoryOwner = $actor->history_owner_id
            && $consultation->guest?->history_owner_id
            && (int) $actor->history_owner_id
                === (int) $consultation
                    ->guest
                    ->history_owner_id;

        if (! $sameDevice && ! $sameHistoryOwner) {
            return false;
        }

        if ($consultation->isPatientHistoryArchived()) {
            return false;
        }

        $actor->loadMissing('historyOwner');

        return ! $actor->historyOwner
            || app(PatientHistoryAccess::class)
                ->isUnlocked(
                    request(),
                    $actor->historyOwner
                );
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
