<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ConsultationPatientProfile extends Model
{
    public const RELATIONSHIPS = [
        'saya' => 'Saya',
        'anak' => 'Anak',
        'pasangan' => 'Pasangan',
        'orang_tua' => 'Orang tua',
        'lainnya' => 'Lainnya',
    ];

    protected $fillable = [
        'history_owner_id',
        'public_id',
        'name',
        'age',
        'phone',
        'relationship',
        'is_default',
        'last_used_at',
    ];

    protected function casts(): array
    {
        return [
            'age' => 'integer',
            'is_default' => 'boolean',
            'last_used_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(
            function (ConsultationPatientProfile $profile): void {
                $profile->public_id ??= (string) Str::uuid();
            }
        );
    }

    public function getRouteKeyName(): string
    {
        return 'public_id';
    }

    public static function relationshipOptions(): array
    {
        return self::RELATIONSHIPS;
    }

    public function relationshipLabel(): string
    {
        return self::RELATIONSHIPS[$this->relationship]
            ?? 'Lainnya';
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
        return $this->hasMany(
            Consultation::class,
            'patient_profile_id'
        );
    }
}
