<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\Statistics\DiffGroupUsersHandler;
use App\Services\Vk\GroupInfoFetcher;
use Illuminate\Console\Command;

class StatisticCommand extends Command
{
    protected $signature = 'vk:demo {groupId}';

    protected $description = 'Демо-команда для управления статистикой';


    public function handle(DiffGroupUsersHandler $handler, GroupInfoFetcher $groupInfoFetcher): int
    {
        $groupId = $this->argument('groupId');
        $handler->handle($groupInfoFetcher->getGroupInfoById($groupId));
        return 0;
    }
}
