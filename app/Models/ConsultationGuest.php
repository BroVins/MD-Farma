<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class ConsultationGuest extends Authenticatable
{
    protected $fillable = [
        'public_id',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    public function consultations()
    {
        return $this->hasMany(Consultation::class, 'guest_id');
    }
}
