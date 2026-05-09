<?php

use App\Services\DiscordService;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Sentry\Laravel\Integration;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->throttleApi();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        Integration::handles($exceptions);

        $exceptions->report(function (\Throwable $e) {
            $request = request();
            DiscordService::send(
                "🚨 **Error 500**\n" .
                "**Endpoint:** " . $request->path() . "\n" .
                "**Método:** " . $request->method() . "\n" .
                "**IP:** " . $request->ip() . "\n" .
                "**Mensaje:** " . $e->getMessage() . "\n" .
                "**Fecha:** " . now()
            );
        });
    })->create();