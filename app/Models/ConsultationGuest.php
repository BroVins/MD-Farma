<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class ConsultationGuest extends Authenticatable
{
    protected $fillable = [
        'public_id',
        'history_owner_id',
        'access_token_hash',
        'device_label',
        'first_seen_at',
        'last_seen_at',
        'revoked_at',
        'expires_at',
    ];

    protected $hidden = [
        'access_token_hash',
    ];

    protected function casts(): array
    {
        return [
            'first_seen_at' => 'datetime',
            'last_seen_at' => 'datetime',
            'revoked_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function historyOwner()
    {
        return $this->belongsTo(
            ConsultationHistoryOwner::class,
            'history_owner_id'
        );
    }

    public function recoveryRecord()
    {
        return $this->hasOne(
            ConsultationDeviceRecovery::class,
            'new_guest_id'
        );
    }

    public function consultations()
    {
        return $this->hasMany(Consultation::class, 'guest_id');
    }

    public function revocationsAsTarget()
    {
        return $this->hasMany(
            ConsultationDeviceRevocation::class,
            'target_guest_id'
        );
    }

    public function revocationsAsActor()
    {
        return $this->hasMany(
            ConsultationDeviceRevocation::class,
            'revoked_by_guest_id'
        );
    }

    public function isActiveDevice(): bool
    {
        return ! $this->revoked_at
            && $this->expires_at
            && $this->expires_at->isFuture();
    }
}
