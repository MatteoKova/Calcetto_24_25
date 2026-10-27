<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

/**
 * Register routes and middlewares
 */
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'isAdmin' => \App\Http\Middleware\isAdminMiddleware::class,
            'isRegisteredUser' => \App\Http\Middleware\isRegisteredUserMiddleware::class,
            'hasNoPlayerYet' => \App\Http\Middleware\hasNoPlayerYetMiddleware::class,
            'lang' => \App\Http\Middleware\languageMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
