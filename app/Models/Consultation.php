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

    protected function casts(): array
    {
        return [
            'first_admin_reply_at' => 'datetime',
            'last_message_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

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

    public function lastMessage()
    {
        return $this->hasOne(Message::class)
            ->latestOfMany('id');
    }

    public function adminReads()
    {
        return $this->hasMany(
            AdminConsultationRead::class
        );
    }

    public function analyticsEvents()
    {
        return $this->hasMany(AnalyticsEvent::class);
    }
}
