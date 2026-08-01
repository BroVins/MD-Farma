<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultationStatusLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'consultation_id',
        'admin_id',
        'previous_status',
        'new_status',
        'reason',
        'created_at',
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
}
