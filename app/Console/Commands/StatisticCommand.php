<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Group;
use App\Services\Statistics\DiffGroupUsersHandler;
use App\Services\Vk\GroupInfoFetcher;
use Illuminate\Console\Command;

class StatisticCommand extends Command
{
    protected $signature = 'vk:demo {vkGroupId}';

    protected $description = 'Демо-команда для управления статистикой';


    public function handle(DiffGroupUsersHandler $handler, GroupInfoFetcher $groupInfoFetcher): int
    {
        $vkGroupId = $this->argument('vkGroupId');
        $vkGroup = $groupInfoFetcher->getGroupInfoById($vkGroupId);
        $group = Group::findByVkGroupIdOrFail($vkGroup->id);
        $handler->handle($group->id);
        return 0;
    }
}
