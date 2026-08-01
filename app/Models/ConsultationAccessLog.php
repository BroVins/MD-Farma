<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultationAccessLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'consultation_id',
        'message_id',
        'archive_copy_request_id',
        'admin_id',
        'action',
        'metadata',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    public function archiveCopyRequest()
    {
        return $this->belongsTo(
            ConsultationArchiveCopyRequest::class,
            'archive_copy_request_id'
        );
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
