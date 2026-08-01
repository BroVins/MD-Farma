<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ConsultationArchiveCopyRequest extends Model
{
    public const STATUSES = [
        'pending' => 'Permintaan baru',
        'verifying' => 'Dalam verifikasi',
        'approved' => 'Disetujui',
        'rejected' => 'Ditolak',
        'completed' => 'Selesai',
    ];

    public const ACTIVE_STATUSES = [
        'pending',
        'verifying',
        'approved',
    ];

    protected $fillable = [
        'public_id',
        'consultation_id',
        'history_owner_id',
        'patient_profile_id',
        'requested_by_guest_id',
        'status',
        'reason',
        'contact_method',
        'contact_value',
        'patient_confirmed_at',
        'submitted_at',
        'processed_by_admin_id',
        'decision_notes',
        'processed_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'patient_confirmed_at' => 'datetime',
            'submitted_at' => 'datetime',
            'processed_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(
            function (ConsultationArchiveCopyRequest $request): void {
                $request->public_id ??= (string) Str::uuid();
                $request->submitted_at ??= now();
            }
        );
    }

    public function getRouteKeyName(): string
    {
        return 'public_id';
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status]
            ?? ucfirst($this->status);
    }

    public function contactMethodLabel(): string
    {
        return match ($this->contact_method) {
            'whatsapp' => 'WhatsApp',
            'telepon' => 'Telepon',
            'ambil_apotek' => 'Ambil di apotek',
            default => ucfirst($this->contact_method),
        };
    }

    public function isActiveRequest(): bool
    {
        return in_array(
            $this->status,
            self::ACTIVE_STATUSES,
            true
        );
    }


    public function accessLogs()
    {
        return $this->hasMany(
            ConsultationAccessLog::class,
            'archive_copy_request_id'
        );
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    public function historyOwner()
    {
        return $this->belongsTo(
            ConsultationHistoryOwner::class,
            'history_owner_id'
        );
    }

    public function patientProfile()
    {
        return $this->belongsTo(
            ConsultationPatientProfile::class,
            'patient_profile_id'
        );
    }

    public function requestedByGuest()
    {
        return $this->belongsTo(
            ConsultationGuest::class,
            'requested_by_guest_id'
        );
    }

    public function processedByAdmin()
    {
        return $this->belongsTo(
            Admin::class,
            'processed_by_admin_id'
        );
    }

    public function logs()
    {
        return $this->hasMany(
            ConsultationArchiveCopyRequestLog::class,
            'archive_copy_request_id'
        )->latest('id');
    }
}
