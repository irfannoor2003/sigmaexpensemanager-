<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// ✅ Import your middleware
use App\Http\Middleware\CustomAuth;
use App\Http\Middleware\RoleMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware): void {

        // ✅ Register route middleware aliases
        $middleware->alias([
            'custom.auth' => CustomAuth::class,
            'role' => RoleMiddleware::class,
            'check.deadline' => \App\Http\Middleware\CheckExpenseDeadline::class, // Add this
        ]);
      $middleware->web(append: [
        \App\Http\Middleware\SetLocale::class,
    ]);
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
