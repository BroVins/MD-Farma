<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultationArchiveCopyRequestLog extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'archive_copy_request_id',
        'admin_id',
        'actor_type',
        'previous_status',
        'new_status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
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
