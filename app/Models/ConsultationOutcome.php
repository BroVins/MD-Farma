<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultationOutcome extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'consultation_id',
        'classification_log_id',
        'screening_id',
        'admin_id',
        'service_classification',
        'outcome_code',
        'outcome_label',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    public function classificationLog()
    {
        return $this->belongsTo(
            ConsultationClassificationLog::class,
            'classification_log_id'
        );
    }

    public function screening()
    {
        return $this->belongsTo(
            ConsultationScreening::class,
            'screening_id'
        );
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
