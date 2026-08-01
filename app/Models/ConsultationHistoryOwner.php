<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ConsultationHistoryOwner extends Model
{
    protected $fillable = [
        'public_id',
        'password_hash',
        'password_set_at',
        'failed_attempts',
        'locked_until',
    ];

    protected $hidden = [
        'password_hash',
    ];

    protected function casts(): array
    {
        return [
            'password_set_at' => 'datetime',
            'failed_attempts' => 'integer',
            'locked_until' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(
            function (ConsultationHistoryOwner $owner): void {
                $owner->public_id ??= (string) Str::uuid();
            }
        );
    }


    public function patientProfiles()
    {
        return $this->hasMany(
            ConsultationPatientProfile::class,
            'history_owner_id'
        );
    }

    public function devices()
    {
        return $this->hasMany(
            ConsultationGuest::class,
            'history_owner_id'
        );
    }


    public function deviceRevocations()
    {
        return $this->hasMany(
            ConsultationDeviceRevocation::class,
            'history_owner_id'
        );
    }

    public function recoveries()
    {
        return $this->hasMany(
            ConsultationDeviceRecovery::class,
            'history_owner_id'
        );
    }

    public function archiveCopyRequests()
    {
        return $this->hasMany(
            ConsultationArchiveCopyRequest::class,
            'history_owner_id'
        );
    }

    public function consultations()
    {
        return $this->hasManyThrough(
            Consultation::class,
            ConsultationGuest::class,
            'history_owner_id',
            'guest_id',
            'id',
            'id'
        );
    }
}
