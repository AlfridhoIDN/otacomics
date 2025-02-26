<?php

use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\CheckPublisher;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'check-admin' => CheckAdmin::class,
            'check-publisher' => CheckPublisher::class,
        ]);
        $middleware->redirectTo(
            guests: 'login',
            users: 'profile'
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
