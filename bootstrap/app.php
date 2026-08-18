<?php

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

if (!function_exists('getRoutePrefix')) {
    function getRoutePrefix()
    {
        return \Illuminate\Support\Facades\Auth::guard('admin')->check() ? 'admin.' : 'coordinator.';
    }
}

if (!function_exists('getRouteUrlPrefix')) {
    function getRouteUrlPrefix()
    {
        return \Illuminate\Support\Facades\Auth::guard('admin')->check() ? 'admin/' : 'coordinator/';
    }
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: [
            __DIR__ . '/../routes/web.php',
            __DIR__ . '/../routes/user-routes.php',
            __DIR__ . '/../routes/admin-routes.php',
            __DIR__ . '/../routes/account-routes.php',
            __DIR__ . '/../routes/coordinator-routes.php',
        ],
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'guest' => RedirectIfAuthenticated::class,
            'auth' => Authenticate::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
