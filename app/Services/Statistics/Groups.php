<?php

declare(strict_types=1);

namespace App\Services\Statistics;

use App\Models\VkUser;

class Groups
{
    public function findGroupIds(): array
    {
        return VkUser::select('group_id')
            ->groupBy('group_id')
            ->pluck('group_id')
            ->toArray();
    }
}
