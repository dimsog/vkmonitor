<?php

declare(strict_types=1);

namespace App\Services\Statistics\Utils;

use App\Services\Telemetry\Telemetry;

class DiffGroupUsersHandlerLog
{
    public function __construct(
        private ?string $logs = null,
        private int $time = 0
    ) {}


    public function log(string $message): void
    {
        if ($this->time > 0) {
            $message = '[' . (time() - $this->time) . ' s] ' . $message . '\n';
        }
        $this->logs .= $message;
        $this->time = time();
    }

    public function sendLog(): void
    {
        Telemetry::info($this->logs);
        $this->logs = null;
    }
}
