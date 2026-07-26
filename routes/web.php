<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/konsultasi', [ConsultationController::class, 'create'])
    ->name('consultation.create');

Route::post('/konsultasi', [ConsultationController::class, 'store'])
    ->name('consultation.store');

Route::get('/chat/{id}', [ChatController::class, 'index'])
    ->whereNumber('id')
    ->name('chat.show');

Route::post('/chat/{id}/send', [MessageController::class, 'store'])
    ->whereNumber('id')
    ->name('chat.send');

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/login', [AdminController::class, 'login'])
        ->name('login');

    Route::post('/login', [AdminController::class, 'authenticate'])
        ->name('authenticate');

    Route::get('/dashboard', [AdminController::class, 'dashboard'])
        ->name('dashboard');

    Route::post('/logout', [AdminController::class, 'logout'])
        ->name('logout');

    Route::post('/chat/{id}/reply', [MessageController::class, 'reply'])
        ->whereNumber('id')
        ->name('chat.reply');
});
