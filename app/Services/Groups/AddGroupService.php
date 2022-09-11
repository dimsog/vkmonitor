<?php

declare(strict_types=1);

namespace App\Services\Groups;

use App\Models\GroupOwner;
use App\Models\Group;
use App\Services\Groups\Dto\Group as GroupDto;
use App\Services\Vk\GroupInfoFetcher;
use App\Services\Vk\Utils\SanitizeVkGroupName;

class AddGroupService
{
    public function __construct(
        private readonly GroupInfoFetcher $groupInfoFetcher
    ) {}

    public function add(string $groupLink, int $userId): GroupDto
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
        GroupOwner::assignGroupAndUser($group->id, $userId);
        return new GroupDto(
            $group->id,
            $vkGroup
        );
    }
}
