<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultationDeviceRevocation extends Model
{
    protected $fillable = [
        'history_owner_id',
        'target_guest_id',
        'revoked_by_guest_id',
        'action',
        'revoked_at',
    ];

    protected function casts(): array
    {
        return [
            'revoked_at' => 'datetime',
        ];
    }

    public function historyOwner()
    {
        return $this->belongsTo(
            ConsultationHistoryOwner::class,
            'history_owner_id'
        );
    }

    public function targetDevice()
    {
        return $this->belongsTo(
            ConsultationGuest::class,
            'target_guest_id'
        );
    }

    public function revokedByDevice()
    {
        return $this->belongsTo(
            ConsultationGuest::class,
            'revoked_by_guest_id'
        );
    }
}
