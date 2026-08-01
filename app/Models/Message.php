<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'consultation_id',
        'sender',
        'message',
        'image',
    ];

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }



    public function accessLogs()
    {
        return $this->hasMany(ConsultationAccessLog::class);
    }

    public function classificationNotice()
    {
        return $this->hasOne(
            ConsultationClassificationNotice::class
        );
    }

    public function isClassificationNotice(): bool
    {
        return $this->relationLoaded('classificationNotice')
            ? $this->classificationNotice !== null
            : $this->classificationNotice()->exists();
    }

    public function attachmentName(): ?string
    {
        if (! $this->image) {
            return null;
        }

        $name = basename($this->image);

        return preg_replace(
            '/^[0-9a-f-]{36}_/i',
            '',
            $name
        ) ?: $name;
    }

    public function attachmentExtension(): ?string
    {
        $name = $this->attachmentName();

        if (! $name) {
            return null;
        }

        $extension = strtolower(
            (string) pathinfo($name, PATHINFO_EXTENSION)
        );

        return $extension !== '' ? $extension : null;
    }

    public function isImageAttachment(): bool
    {
        return in_array(
            $this->attachmentExtension(),
            ['jpg', 'jpeg', 'png', 'webp'],
            true
        );
    }

    public function attachmentType(): ?string
    {
        if (! $this->image) {
            return null;
        }

        return $this->isImageAttachment()
            ? 'image'
            : 'document';
    }
}
