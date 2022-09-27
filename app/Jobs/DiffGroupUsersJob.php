<?php

namespace App\Jobs;

use App\Models\Group;
use App\Services\Statistics\DiffGroupUsersHandler;
use App\Services\Telemetry\Telemetry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DiffGroupUsersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $groupId;

    public int $tries = 3;

    public int $timeout = 14400;


    public function __construct(int $groupId)
    {
        $this->groupId = $groupId;
        $this->onQueue('vkdiff');
    }

    /**
     * Execute the job.
     * @param DiffGroupUsersHandler $diffGroupUsersHandler
     * @throws \Throwable
     * @return void
     */
    public function handle(DiffGroupUsersHandler $diffGroupUsersHandler): void
    {
        $group = Group::find($this->groupId);
        if ($group !== null) {
            $diffGroupUsersHandler->handle($group);
        }
    }

    public function failed(\Throwable $e)
    {
        Telemetry::exception($e);
    }
}
