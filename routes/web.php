<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AdminController;

Route::get('/', [HomeController::class, 'index']);


Route::get(
    '/konsultasi',
    [ConsultationController::class,'create']
);


Route::post(
    '/konsultasi',
    [ConsultationController::class,'store']
);


Route::get(
    '/chat/{id}',
    [ChatController::class, 'index']
);


Route::post(
    '/chat/{id}/send',
    [MessageController::class,'store']
);

Route::get(
    '/admin/login',
    [AdminController::class,'login']
);


Route::post(
    '/admin/login',
    [AdminController::class,'authenticate']
);


Route::get(
    '/admin/dashboard',
    [AdminController::class,'dashboard']
);


Route::get(
    '/admin/logout',
    [AdminController::class,'logout']
);


Route::post(
    '/chat/{id}/reply',
    [MessageController::class,'reply']
);