<?php

namespace Tests\Unit;

use App\Models\Consultation;
use App\Models\ConsultationGuest;
use App\Support\PatientConsultationAccess;
use PHPUnit\Framework\TestCase;

class PatientConsultationAccessTest extends TestCase
{
    public function test_recovered_device_with_same_history_owner_can_access_consultation(): void
    {
        $originalDevice = new ConsultationGuest([
            'history_owner_id' => 10,
        ]);
        $originalDevice->id = 1;

        $recoveredDevice = new ConsultationGuest([
            'history_owner_id' => 10,
        ]);
        $recoveredDevice->id = 2;

        $consultation = new Consultation();
        $consultation->guest_id = 1;
        $consultation->setRelation('guest', $originalDevice);

        $this->assertTrue(
            (new PatientConsultationAccess())->owns(
                $recoveredDevice,
                $consultation
            )
        );
    }

    public function test_device_from_different_history_owner_is_rejected(): void
    {
        $originalDevice = new ConsultationGuest([
            'history_owner_id' => 10,
        ]);
        $originalDevice->id = 1;

        $otherDevice = new ConsultationGuest([
            'history_owner_id' => 11,
        ]);
        $otherDevice->id = 3;

        $consultation = new Consultation();
        $consultation->guest_id = 1;
        $consultation->setRelation('guest', $originalDevice);

        $this->assertFalse(
            (new PatientConsultationAccess())->owns(
                $otherDevice,
                $consultation
            )
        );
    }
}
