<?php

namespace App\Support;

use App\Models\ConsultationHistoryOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PatientHistoryAccess
{
    private const SESSION_OWNER_KEY =
        'md_farma_history_owner_id';

    private const SESSION_UNLOCKED_UNTIL_KEY =
        'md_farma_history_unlocked_until';

    public function isUnlocked(
        Request $request,
        ConsultationHistoryOwner $owner
    ): bool {
        $ownerId = (int) $request->session()->get(
            self::SESSION_OWNER_KEY,
            0
        );

        $unlockedUntil = (int) $request->session()->get(
            self::SESSION_UNLOCKED_UNTIL_KEY,
            0
        );

        return $ownerId === (int) $owner->getKey()
            && $unlockedUntil > now()->timestamp;
    }

    public function unlock(
        Request $request,
        ConsultationHistoryOwner $owner
    ): void {
        $minutes = max(
            5,
            (int) config(
                'consultation.history_unlock_minutes',
                480
            )
        );

        $request->session()->put([
            self::SESSION_OWNER_KEY => (int) $owner->getKey(),
            self::SESSION_UNLOCKED_UNTIL_KEY =>
                now()->addMinutes($minutes)->timestamp,
        ]);
    }

    public function lock(Request $request): void
    {
        $request->session()->forget([
            self::SESSION_OWNER_KEY,
            self::SESSION_UNLOCKED_UNTIL_KEY,
        ]);
    }

    public function verifyPassword(
        ConsultationHistoryOwner $owner,
        string $password
    ): bool {
        if (
            $owner->locked_until
            && $owner->locked_until->isFuture()
        ) {
            return false;
        }

        if (! Hash::check($password, $owner->password_hash)) {
            $attempts = (int) $owner->failed_attempts + 1;
            $maxAttempts = max(
                3,
                (int) config(
                    'consultation.history_password_max_attempts',
                    5
                )
            );

            $changes = [
                'failed_attempts' => $attempts,
            ];

            if ($attempts >= $maxAttempts) {
                $changes['locked_until'] = now()->addMinutes(
                    max(
                        5,
                        (int) config(
                            'consultation.history_password_lock_minutes',
                            15
                        )
                    )
                );
                $changes['failed_attempts'] = 0;
            }

            $owner->forceFill($changes)->save();

            return false;
        }

        if (
            (int) $owner->failed_attempts !== 0
            || $owner->locked_until !== null
        ) {
            $owner->forceFill([
                'failed_attempts' => 0,
                'locked_until' => null,
            ])->save();
        }

        return true;
    }
}
