<?php

namespace App\Http\Controllers;

use App\Models\ConsultationDeviceRevocation;
use App\Models\ConsultationGuest;
use App\Models\ConsultationHistoryOwner;
use App\Support\PatientAccessCookie;
use App\Support\PatientHistoryAccess;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class ConsultationDeviceController extends Controller
{
    public function index(
        Request $request,
        PatientAccessCookie $accessCookie,
        PatientHistoryAccess $historyAccess
    ): View|RedirectResponse {
        $context = $this->unlockedContext(
            $request,
            $accessCookie,
            $historyAccess
        );

        if ($context instanceof RedirectResponse) {
            return $context;
        }

        [$currentDevice, $owner] = $context;
        $accessCookie->refresh($request, $currentDevice);

        $devices = $owner->devices()
            ->orderByRaw(
                'CASE WHEN consultation_guests.id = ? THEN 0 '
                .'WHEN consultation_guests.revoked_at IS NULL '
                .'AND consultation_guests.expires_at > ? THEN 1 '
                .'ELSE 2 END',
                [$currentDevice->id, now()]
            )
            ->orderByDesc('consultation_guests.last_seen_at')
            ->orderByDesc('consultation_guests.id')
            ->get();

        $activeDeviceTotal = $devices
            ->filter(
                fn (ConsultationGuest $device): bool =>
                    $device->isActiveDevice()
            )
            ->count();

        $revocations = $owner->deviceRevocations()
            ->with([
                'targetDevice:id,public_id,device_label',
                'revokedByDevice:id,public_id,device_label',
            ])
            ->orderByDesc('revoked_at')
            ->orderByDesc('id')
            ->limit(20)
            ->get();

        return view('consultation.devices', [
            'currentDevice' => $currentDevice,
            'devices' => $devices,
            'activeDeviceTotal' => $activeDeviceTotal,
            'revocations' => $revocations,
        ]);
    }

    public function revoke(
        Request $request,
        ConsultationGuest $device,
        PatientAccessCookie $accessCookie,
        PatientHistoryAccess $historyAccess
    ): RedirectResponse {
        $context = $this->unlockedContext(
            $request,
            $accessCookie,
            $historyAccess
        );

        if ($context instanceof RedirectResponse) {
            return $context;
        }

        [$currentDevice, $owner] = $context;

        abort_unless(
            (int) $device->history_owner_id
                === (int) $owner->id,
            404
        );

        if ((int) $device->id === (int) $currentDevice->id) {
            return redirect()
                ->route('consultation.devices.index')
                ->with(
                    'warning',
                    'Gunakan tombol Hapus akses perangkat ini untuk mencabut perangkat yang sedang digunakan.'
                );
        }

        $revoked = DB::transaction(
            function () use (
                $device,
                $currentDevice,
                $owner
            ): bool {
                $target = ConsultationGuest::query()
                    ->lockForUpdate()
                    ->find($device->id);

                if (! $target
                    || (int) $target->history_owner_id
                        !== (int) $owner->id
                    || ! $target->isActiveDevice()) {
                    return false;
                }

                $this->revokeDevice(
                    $owner,
                    $target,
                    $currentDevice,
                    'single'
                );

                return true;
            }
        );

        return redirect()
            ->route('consultation.devices.index')
            ->with(
                $revoked ? 'status' : 'warning',
                $revoked
                    ? 'Akses perangkat berhasil dicabut.'
                    : 'Perangkat tersebut sudah tidak memiliki akses aktif.'
            );
    }

    public function revokeOthers(
        Request $request,
        PatientAccessCookie $accessCookie,
        PatientHistoryAccess $historyAccess
    ): RedirectResponse {
        $context = $this->unlockedContext(
            $request,
            $accessCookie,
            $historyAccess
        );

        if ($context instanceof RedirectResponse) {
            return $context;
        }

        [$currentDevice, $owner] = $context;

        $revokedCount = DB::transaction(
            function () use ($currentDevice, $owner): int {
                $targets = ConsultationGuest::query()
                    ->where('history_owner_id', $owner->id)
                    ->where('id', '!=', $currentDevice->id)
                    ->whereNull('revoked_at')
                    ->where('expires_at', '>', now())
                    ->lockForUpdate()
                    ->get();

                foreach ($targets as $target) {
                    $this->revokeDevice(
                        $owner,
                        $target,
                        $currentDevice,
                        'all_others'
                    );
                }

                return $targets->count();
            }
        );

        return redirect()
            ->route('consultation.devices.index')
            ->with(
                $revokedCount > 0 ? 'status' : 'warning',
                $revokedCount > 0
                    ? $revokedCount.' perangkat lain berhasil dicabut.'
                    : 'Tidak ada perangkat lain dengan akses aktif.'
            );
    }

    public function revokeCurrent(
        Request $request,
        PatientAccessCookie $accessCookie,
        PatientHistoryAccess $historyAccess
    ): RedirectResponse {
        $context = $this->unlockedContext(
            $request,
            $accessCookie,
            $historyAccess
        );

        if ($context instanceof RedirectResponse) {
            return $context;
        }

        [$currentDevice, $owner] = $context;

        DB::transaction(
            function () use ($currentDevice, $owner): void {
                $target = ConsultationGuest::query()
                    ->lockForUpdate()
                    ->findOrFail($currentDevice->id);

                if ($target->isActiveDevice()) {
                    $this->revokeDevice(
                        $owner,
                        $target,
                        $target,
                        'current'
                    );
                }
            }
        );

        $historyAccess->lock($request);
        Auth::guard('patient')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('consultation.create')
            ->with(
                'status',
                'Akses riwayat telah dihapus dari perangkat ini. Data konsultasi tetap tersimpan.'
            )
            ->withCookie(
                Cookie::forget(
                    (string) config(
                        'consultation.patient_cookie',
                        'md_farma_patient_access'
                    ),
                    '/',
                    config('session.domain')
                )
            );
    }

    private function unlockedContext(
        Request $request,
        PatientAccessCookie $accessCookie,
        PatientHistoryAccess $historyAccess
    ): array|RedirectResponse {
        $guest = $accessCookie->restore($request);

        if (! $guest) {
            return redirect()
                ->route('consultation.create')
                ->with(
                    'warning',
                    'Perangkat ini belum terhubung ke riwayat konsultasi.'
                );
        }

        $guest->loadMissing('historyOwner');
        $owner = $guest->historyOwner;

        if (! $owner) {
            return redirect()->route('consultation.entry');
        }

        if (! $historyAccess->isUnlocked($request, $owner)) {
            return redirect()
                ->route('consultation.entry')
                ->with(
                    'warning',
                    'Masukkan Password Riwayat sebelum mengelola perangkat.'
                );
        }

        return [$guest, $owner];
    }

    private function revokeDevice(
        ConsultationHistoryOwner $owner,
        ConsultationGuest $target,
        ConsultationGuest $actor,
        string $action
    ): void {
        $revokedAt = now();

        $target->forceFill([
            'access_token_hash' => null,
            'revoked_at' => $revokedAt,
            'expires_at' => $revokedAt,
        ])->save();

        ConsultationDeviceRevocation::create([
            'history_owner_id' => $owner->id,
            'target_guest_id' => $target->id,
            'revoked_by_guest_id' => $actor->id,
            'action' => $action,
            'revoked_at' => $revokedAt,
        ]);
    }
}
