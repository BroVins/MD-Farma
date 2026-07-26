<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class AnalyticsEvent extends Model
{
    protected $fillable = [
        'event_key',
        'session_hash',
        'event_type',
        'consultation_id',
        'metadata',
        'occurred_at',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'occurred_at' => 'datetime',
        ];
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    public static function recordOnce(
        Request $request,
        string $eventType,
        ?Consultation $consultation = null,
        array $metadata = [],
        ?string $deduplicationKey = null
    ): ?self {
        if (! $request->hasSession()) {
            return null;
        }

        $sessionId = $request->session()->getId();

        if ($sessionId === '') {
            return null;
        }

        $sessionHash = hash('sha256', $sessionId);
        $deduplicationKey ??= now()
            ->timezone(config('analytics.timezone', 'Asia/Jakarta'))
            ->format('Y-m-d');

        $eventKey = hash('sha256', implode('|', [
            $sessionHash,
            $eventType,
            (string) ($consultation?->id ?? 0),
            $deduplicationKey,
        ]));

        try {
            return self::firstOrCreate(
                ['event_key' => $eventKey],
                [
                    'session_hash' => $sessionHash,
                    'event_type' => $eventType,
                    'consultation_id' => $consultation?->id,
                    'metadata' => $metadata ?: null,
                    'occurred_at' => now(),
                ]
            );
        } catch (QueryException) {
            return self::where('event_key', $eventKey)->first();
        }
    }
}
