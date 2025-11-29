<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        // si algún día usas API puedes descomentar esta línea:
        // api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // Aquí registramos aliases de middleware por nombre
        $middleware->alias([
        'auth'  => \App\Http\Middleware\Authenticate::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,

        // 👉 AQUI REGISTRAMOS TU MIDDLEWARE
        'is_admin' => \App\Http\Middleware\IsAdmin::class,
    ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
