<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultationClassificationNotice extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'consultation_id',
        'classification_log_id',
        'message_id',
        'admin_id',
        'template_code',
        'service_classification',
        'content_snapshot',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
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

    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
