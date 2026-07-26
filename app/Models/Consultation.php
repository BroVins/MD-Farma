<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Consultation extends Model
{
    protected $fillable = [
        'nama',
        'umur',
        'no_hp',
        'jenis_konsultasi',
        'status',
    ];

    protected static function booted(): void
    {
        static::creating(
            function (Consultation $consultation): void {
                $consultation->public_id ??=
                    (string) Str::uuid();
            }
        );
    }

    public function getRouteKeyName(): string
    {
        return 'public_id';
    }

    public function guest()
    {
        return $this->belongsTo(
            ConsultationGuest::class,
            'guest_id'
        );
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
