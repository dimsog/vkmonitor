<?php

declare(strict_types=1);

namespace App\Services\Statistics;

use App\Models\VkUser;

class GroupExistsChecker
{
    public function isExists(int $groupId): bool
    {
        return VkUser::where('group_id', $groupId)
            ->exists();
    }
}
