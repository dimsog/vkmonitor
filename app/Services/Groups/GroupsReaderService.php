<?php

namespace App\Services\Groups;

use App\Models\Group;
use App\Services\Vk\GroupInfoFetcher;
use App\Services\Groups\Dto\Group as GroupDto;

class GroupsReaderService
{
    public function __construct(
        private readonly GroupInfoFetcher $groupInfoFetcher
    )
    {
    }

    public function read(int $userId): array
    {
        $groups = Group::findGroupsByUser($userId);
        $groupIds = $groups->pluck('vk_group_id')->toArray();
        if (empty($groupIds)) {
            return [];
        }
        $vkGroups = $this->groupInfoFetcher->getGroupInfoByIds($groupIds);
        $result = [];
        foreach ($groups as $group) {
            $result[] = new GroupDto(
                $group->id,
                $vkGroups->where('id', $group->vk_group_id)->first()
            );
        }
        return $result;
    }
}
