<?php

namespace App\Providers;

use App\Services\DiscordService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(10)
                ->by($request->user()?->id ?: $request->ip())
                ->response(function (Request $request, array $headers) {
                    DiscordService::send(
                        "⚠️ **Rate Limit Excedido**\n" .
                        "**Endpoint:** " . $request->path() . "\n" .
                        "**Método:** " . $request->method() . "\n" .
                        "**IP:** " . $request->ip() . "\n" .
                        "**Intentos:** más de " . 10 . " por minuto\n" .
                        "**Fecha:** " . now()
                    );

                    return response()->json([
                        'success' => false,
                        'message' => 'Demasiadas solicitudes. Intenta de nuevo en un minuto.',
                    ], 429, $headers);
                });
        });
    }
}