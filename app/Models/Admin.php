<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $fillable = [
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function consultationReads()
    {
        return $this->hasMany(
            AdminConsultationRead::class
        );
    }


    public function classifiedConsultations()
    {
        return $this->hasMany(
            Consultation::class,
            'classified_by_admin_id'
        );
    }

    public function classificationChanges()
    {
        return $this->hasMany(
            ConsultationClassificationLog::class
        );
    }


    public function classificationNotices()
    {
        return $this->hasMany(
            ConsultationClassificationNotice::class
        );
    }
}
