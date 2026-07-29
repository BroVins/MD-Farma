<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminInboxController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

Route::get(
    '/',
    [HomeController::class, 'index']
)->name('home');
Route::get(
    '/kerja-sama',
    [HomeController::class, 'partnership']
)->name('partnership');

Route::get(
    '/konsultasi',
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
    ->middleware('consultation.access')
    ->name('chat.show');

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

                Route::post(
                    '/inbox/{consultation:public_id}/read',
                    [AdminInboxController::class, 'markRead']
                )
                    ->middleware('throttle:180,1')
                    ->name('inbox.read');

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
