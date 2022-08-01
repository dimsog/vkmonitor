<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\ImportUsersFromGroup;
use App\Models\Group;
use Illuminate\Console\Command;

class AddAllGroupsToQueueCommand extends Command
{
    protected $signature = 'queue:groups';

    protected $description = 'Команда добавляет все группы в очередь';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        foreach (Group::all() as $group) {
            ImportUsersFromGroup::dispatch($group->id);
        }
        return 0;
    }
}
