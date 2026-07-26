<?php

use App\Http\Middleware\EnsureConsultationAccess;
use App\Http\Middleware\EnsurePatientConsultationAccess;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'consultation.access' =>
                EnsureConsultationAccess::class,

            'consultation.patient' =>
                EnsurePatientConsultationAccess::class,
        ]);

        $middleware->redirectGuestsTo(
            fn (Request $request): string =>
                $request->is('admin/*')
                    ? '/admin/login'
                    : '/'
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request): bool =>
                $request->is('api/*')
        );
    })
    ->create();
