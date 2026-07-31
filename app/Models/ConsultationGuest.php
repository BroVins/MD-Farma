<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class ConsultationGuest extends Authenticatable
{
    protected $fillable = [
        'public_id',
        'history_owner_id',
        'access_token_hash',
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

    public function consultations()
    {
        return $this->hasMany(Consultation::class, 'guest_id');
    }
}
