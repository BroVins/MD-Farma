<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultationDeviceRecovery extends Model
{
    protected $fillable = [
        'history_owner_id',
        'source_consultation_id',
        'new_guest_id',
        'recovery_method',
        'phone_hash',
        'recovered_at',
    ];

    protected $hidden = [
        'phone_hash',
    ];

    protected function casts(): array
    {
        return [
            'recovered_at' => 'datetime',
        ];
    }

    public function historyOwner()
    {
        return $this->belongsTo(
            ConsultationHistoryOwner::class,
            'history_owner_id'
        );
    }

    public function sourceConsultation()
    {
        return $this->belongsTo(
            Consultation::class,
            'source_consultation_id'
        );
    }

    public function newDevice()
    {
        return $this->belongsTo(
            ConsultationGuest::class,
            'new_guest_id'
        );
    }
}
