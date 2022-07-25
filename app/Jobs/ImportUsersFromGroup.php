<?php

namespace App\Jobs;

use App\Services\Statistics\DiffGroupUsersHandler;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportUsersFromGroup implements ShouldQueue, ShouldBeUnique
{
    private const RELEASE_AFTER_HOURS = 4;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;


    public function __construct(
        private readonly int $vkGroupId
    )
    {
        $this->onQueue('groups');
    }

    public function handle(DiffGroupUsersHandler $statisticHandler): void
    {
        $statisticHandler->handle($this->vkGroupId);

        $this->release(now()->addHours(self::RELEASE_AFTER_HOURS));
    }

    public function uniqueId(): int
    {
        return $this->vkGroupId;
    }
}