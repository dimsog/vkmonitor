<?php

declare(strict_types=1);

namespace App\Services\Telemetry;

use Illuminate\Support\Facades\Log;

class Telemetry
{
    public static function info(string $message): void
    {
        Log::channel('telegram-info')->info($message);
    }

    public static function error(string $message): void
    {
        Log::channel('telegram-info')->error($message);
    }

    public static function exception(\Throwable $e): void
    {
        $message = "Error: " . $e->getMessage() . PHP_EOL;
        $message .= "File: " . $e->getFile() . PHP_EOL;
        $message .= "Line: " . $e->getLine();

        static::error($message);
    }
}
