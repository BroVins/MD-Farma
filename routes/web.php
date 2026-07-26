<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman publik
|--------------------------------------------------------------------------
*/

Route::get(
    '/',
    [HomeController::class, 'index']
)->name('home');

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
    '/chat/{id}',
    [ChatController::class, 'index']
)
    ->whereNumber('id')
    ->name('chat.show');

Route::post(
    '/chat/{id}/send',
    [MessageController::class, 'store']
)
    ->whereNumber('id')
    ->middleware('throttle:30,1')
    ->name('chat.send');

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/

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
                Route::get(
                    '/dashboard',
                    [AdminController::class, 'dashboard']
                )->name('dashboard');

                Route::post(
                    '/logout',
                    [AdminController::class, 'logout']
                )->name('logout');

                Route::post(
                    '/chat/{id}/reply',
                    [MessageController::class, 'reply']
                )
                    ->whereNumber('id')
                    ->middleware('throttle:30,1')
                    ->name('chat.reply');
            });
    });
