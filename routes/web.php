<?php

use App\Http\Controllers\AdminArchiveCopyRequestController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminInboxController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ConsultationArchiveCopyRequestController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\ConsultationDeviceController;
use App\Http\Controllers\ConsultationPatientProfileController;
use App\Http\Controllers\ConsultationRecoveryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

Route::get(
    '/',
    [HomeController::class, 'index']
)->name('home');

Route::get(
    '/profil',
    [HomeController::class, 'profile']
)->name('profile');

Route::get(
    '/kerja-sama',
    [HomeController::class, 'partnership']
)->name('partnership');

Route::get(
    '/konsultasi',
    [ConsultationController::class, 'entry']
)->name('consultation.entry');

Route::get(
    '/konsultasi/pulihkan',
    [ConsultationRecoveryController::class, 'show']
)->name('consultation.recovery.show');

Route::post(
    '/konsultasi/pulihkan',
    [ConsultationRecoveryController::class, 'verify']
)
    ->middleware('throttle:5,1')
    ->name('consultation.recovery.verify');

Route::get(
    '/konsultasi/pulihkan/konfirmasi',
    [ConsultationRecoveryController::class, 'confirmation']
)->name('consultation.recovery.confirm.show');

Route::post(
    '/konsultasi/pulihkan/konfirmasi',
    [ConsultationRecoveryController::class, 'confirm']
)
    ->middleware('throttle:10,1')
    ->name('consultation.recovery.confirm');

Route::get(
    '/konsultasi/riwayat',
    [ConsultationController::class, 'history']
)->name('consultation.history');


Route::post(
    '/konsultasi/riwayat/{consultation:public_id}/salinan',
    [ConsultationArchiveCopyRequestController::class, 'store']
)
    ->middleware('throttle:5,1')
    ->name('consultation.archive-copy.store');

Route::get(
    '/konsultasi/profil-pasien',
    [ConsultationPatientProfileController::class, 'index']
)->name('consultation.profiles.index');

Route::post(
    '/konsultasi/profil-pasien',
    [ConsultationPatientProfileController::class, 'store']
)
    ->middleware('throttle:20,1')
    ->name('consultation.profiles.store');

Route::put(
    '/konsultasi/profil-pasien/{profile:public_id}',
    [ConsultationPatientProfileController::class, 'update']
)
    ->middleware('throttle:30,1')
    ->name('consultation.profiles.update');

Route::post(
    '/konsultasi/profil-pasien/{profile:public_id}/utama',
    [ConsultationPatientProfileController::class, 'makeDefault']
)
    ->middleware('throttle:20,1')
    ->name('consultation.profiles.default');

Route::get(
    '/konsultasi/keamanan-perangkat',
    [ConsultationDeviceController::class, 'index']
)->name('consultation.devices.index');

Route::post(
    '/konsultasi/keamanan-perangkat/cabut-lainnya',
    [ConsultationDeviceController::class, 'revokeOthers']
)
    ->middleware('throttle:10,1')
    ->name('consultation.devices.revoke-others');

Route::post(
    '/konsultasi/keamanan-perangkat/cabut-perangkat-ini',
    [ConsultationDeviceController::class, 'revokeCurrent']
)
    ->middleware('throttle:5,1')
    ->name('consultation.devices.revoke-current');

Route::post(
    '/konsultasi/keamanan-perangkat/{device:public_id}/cabut',
    [ConsultationDeviceController::class, 'revoke']
)
    ->middleware('throttle:20,1')
    ->name('consultation.devices.revoke');

Route::post(
    '/konsultasi/buka-riwayat',
    [ConsultationController::class, 'unlockHistory']
)
    ->middleware('throttle:10,1')
    ->name('consultation.history.unlock');

Route::post(
    '/konsultasi/aktifkan-password',
    [ConsultationController::class, 'setupHistoryPassword']
)
    ->middleware('throttle:10,1')
    ->name('consultation.history.setup');

Route::post(
    '/konsultasi/kunci-riwayat',
    [ConsultationController::class, 'lockHistory']
)
    ->middleware('throttle:20,1')
    ->name('consultation.history.lock');

Route::get(
    '/konsultasi/baru',
    [ConsultationController::class, 'create']
)->name('consultation.create');

Route::post(
    '/konsultasi',
    [ConsultationController::class, 'store']
)
    ->middleware('throttle:10,1')
    ->name('consultation.store');

Route::get(
    '/chat/{consultation:public_id}',
    [ChatController::class, 'index']
)
    ->middleware('consultation.patient')
    ->name('chat.show');

Route::get(
    '/chat/{consultation:public_id}/messages',
    [MessageController::class, 'index']
)
    ->middleware([
        'consultation.patient',
        'throttle:120,1',
    ])
    ->name('chat.messages');

Route::post(
    '/chat/{consultation:public_id}/send',
    [MessageController::class, 'store']
)
    ->middleware([
        'consultation.patient',
        'throttle:30,1',
    ])
    ->name('chat.send');

Route::get(
    '/chat/{consultation:public_id}/attachment/{message}',
    [MessageController::class, 'attachment']
)
    ->whereNumber('message')
    ->middleware('consultation.access')
    ->name('chat.attachment');

Route::prefix('admin')
    ->name('admin.')
    ->group(function (): void {
        Route::get(
            '/login',
            [AdminController::class, 'login']
        )->name('login');

        Route::post(
            '/login',
            [AdminController::class, 'authenticate']
        )
            ->middleware('throttle:5,1')
            ->name('authenticate');

        Route::middleware('auth:admin')
            ->group(function (): void {
                /*
                |----------------------------------------------------------
                | Inbox operasional admin
                |----------------------------------------------------------
                */

                Route::get(
                    '/inbox',
                    [AdminInboxController::class, 'index']
                )->name('inbox');

                Route::get(
                    '/inbox/live',
                    [AdminInboxController::class, 'liveData']
                )
                    ->middleware('throttle:180,1')
                    ->name('inbox.live');

                Route::get(
                    '/inbox/{consultation:public_id}',
                    [AdminInboxController::class, 'show']
                )->name('inbox.show');

                Route::get(
                    '/inbox/{consultation:public_id}/conversation',
                    [AdminInboxController::class, 'conversation']
                )
                    ->middleware('throttle:180,1')
                    ->name('inbox.conversation');

                Route::get(
                    '/inbox/{consultation:public_id}/messages',
                    [MessageController::class, 'index']
                )
                    ->middleware('throttle:120,1')
                    ->name('inbox.messages');

                Route::post(
                    '/inbox/{consultation:public_id}/read',
                    [AdminInboxController::class, 'markRead']
                )
                    ->middleware('throttle:180,1')
                    ->name('inbox.read');

                Route::post(
                    '/inbox/{consultation:public_id}/classification',
                    [AdminInboxController::class, 'updateClassification']
                )
                    ->middleware('throttle:30,1')
                    ->name('inbox.classification');

                Route::post(
                    '/inbox/{consultation:public_id}/screening',
                    [AdminInboxController::class, 'updateScreening']
                )
                    ->middleware('throttle:60,1')
                    ->name('inbox.screening');

                Route::post(
                    '/inbox/{consultation:public_id}/outcome',
                    [AdminInboxController::class, 'updateOutcome']
                )
                    ->middleware('throttle:60,1')
                    ->name('inbox.outcome');


                Route::get(
                    '/permintaan-arsip',
                    [AdminArchiveCopyRequestController::class, 'index']
                )->name('archive-requests.index');

                Route::get(
                    '/permintaan-arsip/{archiveCopyRequest:public_id}',
                    [AdminArchiveCopyRequestController::class, 'show']
                )->name('archive-requests.show');

                Route::put(
                    '/permintaan-arsip/{archiveCopyRequest:public_id}',
                    [AdminArchiveCopyRequestController::class, 'update']
                )
                    ->middleware('throttle:30,1')
                    ->name('archive-requests.update');

                /*
                |----------------------------------------------------------
                | Dashboard analitik
                |----------------------------------------------------------
                */

                Route::get(
                    '/dashboard',
                    [AdminController::class, 'dashboard']
                )->name('dashboard');

                Route::get(
                    '/dashboard/live',
                    [AdminController::class, 'liveData']
                )
                    ->middleware('throttle:120,1')
                    ->name('dashboard.live');

                Route::post(
                    '/logout',
                    [AdminController::class, 'logout']
                )->name('logout');

                Route::post(
                    '/chat/{consultation:public_id}/reply',
                    [MessageController::class, 'reply']
                )
                    ->middleware('throttle:30,1')
                    ->name('chat.reply');

                Route::post(
                    '/chat/{consultation:public_id}/status',
                    [AdminController::class, 'updateStatus']
                )
                    ->middleware('throttle:20,1')
                    ->name('chat.status');
            });
    });
