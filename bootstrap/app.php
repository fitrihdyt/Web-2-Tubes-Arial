<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\IsHotelAdmin;
use App\Http\Middleware\IsSuperAdmin;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUser;

use App\Http\Middleware\NotAdmin;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'IsHotelAdmin' => IsHotelAdmin::class,
            'IsSuperAdmin' => IsSuperAdmin::class,
            'IsUser'       => IsUser::class,
            'notAdmin'     => NotAdmin::class,
            'IsAdmin'      => IsAdmin::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'payment/midtrans-callback',
        ]);

        // ❌ JANGAN append middleware role ke web
        // BIARKAN KOSONG
        
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
