<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultationScreening extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'consultation_id',
        'classification_log_id',
        'admin_id',
        'service_classification',
        'answers',
        'notes',
        'required_count',
        'completed_count',
        'is_complete',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'answers' => 'array',
            'is_complete' => 'boolean',
            'completed_at' => 'datetime',
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

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
