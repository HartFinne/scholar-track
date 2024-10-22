<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\ScholarAuth;
use App\Http\Middleware\staff;
use App\Http\Middleware\applicant;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'scholar' => ScholarAuth::class,
            'staff' => staff::class,
            'applicant' => applicant::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {})->create();
