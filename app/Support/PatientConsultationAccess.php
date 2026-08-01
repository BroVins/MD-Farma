<?php

namespace App\Support;

use App\Models\Consultation;
use App\Models\ConsultationGuest;

class PatientConsultationAccess
{
    public function owns(
        ConsultationGuest $guest,
        Consultation $consultation
    ): bool {
        if (! $consultation->relationLoaded('guest')) {
            $consultation->loadMissing(
                'guest:id,history_owner_id'
            );
        }

        $sameDevice = (int) $consultation->guest_id
            === (int) $guest->getAuthIdentifier();

        $sameHistoryOwner = $guest->history_owner_id
            && $consultation->guest?->history_owner_id
            && (int) $guest->history_owner_id
                === (int) $consultation
                    ->guest
                    ->history_owner_id;

        return $sameDevice || $sameHistoryOwner;
    }
}
