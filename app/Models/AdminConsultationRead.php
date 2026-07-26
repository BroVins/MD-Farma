<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminConsultationRead extends Model
{
    protected $fillable = [
        'admin_id',
        'consultation_id',
        'last_read_message_id',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    public function lastReadMessage()
    {
        return $this->belongsTo(
            Message::class,
            'last_read_message_id'
        );
    }
}
