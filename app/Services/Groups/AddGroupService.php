<?php

declare(strict_types=1);

namespace App\Services\Groups;

use App\Models\GroupOwner;
use App\Models\Group;
use App\Services\Vk\GroupInfoFetcher;
use App\Services\Vk\Utils\SanitizeVkGroupName;

class AddGroupService
{
    public function __construct(
        private readonly GroupInfoFetcher $groupInfoFetcher
    ) {}

    public function add(string $groupLink, int $userId): bool
    {
        $vkGroup = $this->groupInfoFetcher->getGroupInfoById(
            SanitizeVkGroupName::sanitize($groupLink)
        );
        $group = Group::findByVkGroup($vkGroup->id);
        if ($group === null) {
            $group = new Group();
            $group->vk_group_id = $vkGroup->id;
            $group->save();
        }
        return GroupOwner::assignGroupAndUser($group->id, $userId);
    }
}
