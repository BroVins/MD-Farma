<?php

namespace App\Support;

use App\Models\Consultation;
use App\Models\ConsultationAccessLog;
use App\Models\ConsultationArchiveCopyRequest;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsultationAudit
{
    public function recordAccess(
        Request $request,
        string $action,
        Consultation $consultation,
        ?Message $message = null,
        ?ConsultationArchiveCopyRequest $archiveRequest = null,
        array $metadata = [],
        int $deduplicateMinutes = 15
    ): void {
        $adminId = Auth::guard('admin')->id();

        if (! $adminId) {
            return;
        }

        $dedupeKey = 'consultation_audit.' . hash(
            'sha256',
            implode('|', [
                $action,
                (string) $consultation->getKey(),
                (string) ($message?->getKey() ?? 0),
                (string) ($archiveRequest?->getKey() ?? 0),
            ])
        );

        $lastRecordedAt = (int) $request->session()->get(
            $dedupeKey,
            0
        );

        if (
            $deduplicateMinutes > 0
            && $lastRecordedAt > now()
                ->subMinutes($deduplicateMinutes)
                ->timestamp
        ) {
            return;
        }

        $ip = (string) $request->ip();
        $appKey = (string) config('app.key', 'md-farma');

        ConsultationAccessLog::create([
            'consultation_id' => $consultation->getKey(),
            'message_id' => $message?->getKey(),
            'archive_copy_request_id' => $archiveRequest?->getKey(),
            'admin_id' => $adminId,
            'action' => $action,
            'metadata' => array_filter(array_merge([
                'ip_hash' => $ip !== ''
                    ? hash_hmac('sha256', $ip, $appKey)
                    : null,
                'user_agent' => substr(
                    (string) $request->userAgent(),
                    0,
                    255
                ),
            ], $metadata), static fn (mixed $value): bool =>
                $value !== null && $value !== ''),
            'created_at' => now(),
        ]);

        $request->session()->put(
            $dedupeKey,
            now()->timestamp
        );
    }
}
