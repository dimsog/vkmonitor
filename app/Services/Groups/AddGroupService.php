<?php

declare(strict_types=1);

namespace App\Services\Groups;

use App\Services\Groups\Exceptions\AddGroupException;
use App\Models\Group;

class AddGroupService
{
    public function add(int $userId, int $vkGroupId, int $vkClientId, string $vkAccessToken): Group
    {
        $group = new Group();
        $group->user_id = $userId;
        $group->vk_group_id = $vkGroupId;
        $group->vk_client_id = $vkClientId;
        $group->vk_access_token = $vkAccessToken;
        if (!$group->save()) {
            throw new AddGroupException('Не удалось сохранить группу');
        }
        return $group;
    }
}
