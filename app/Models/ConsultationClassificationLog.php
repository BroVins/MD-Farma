<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultationClassificationLog extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'consultation_id',
        'admin_id',
        'previous_classification',
        'new_classification',
        'reason',
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

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }


    public function notice()
    {
        return $this->hasOne(
            ConsultationClassificationNotice::class,
            'classification_log_id'
        );
    }
}
