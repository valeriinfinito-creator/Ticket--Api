<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DiscordService
{
    public static function send(string $message): void
    {
        $url = config('services.discord.webhook');

        if (!$url) return;

        Http::post($url, [
            'content' => $message,
        ]);
    }
}