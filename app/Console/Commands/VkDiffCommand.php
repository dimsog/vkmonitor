<?php

namespace App\Console\Commands;

use App\Jobs\DiffGroupUsersJob;
use App\Models\Group;
use Illuminate\Console\Command;

class VkDiffCommand extends Command
{
    protected $signature = 'vk:diff';

    protected $description = 'Команда вычисляет, кто подписался, кто отписался';


    public function handle(): int
    {
        foreach (Group::findAllGroups() as $group) {
            /** @var Group $group */
            DiffGroupUsersJob::dispatch($group->id);
        }
        return 0;
    }
}
