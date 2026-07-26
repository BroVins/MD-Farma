<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    protected $fillable = [
        'nama',
        'umur',
        'no_hp',
        'jenis_konsultasi',
        'status'
    ];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}