<?php

declare(strict_types=1);

namespace App\Services\Statistics;

use App\Models\Group;
use App\Models\GroupUser;
use App\Services\Vk\GroupInfoFetcher;
use App\Services\Vk\GroupUsers;

/**
 * Сервис занимается поиском подписавшихся и отписавшихся пользователей
 */
class DiffGroupUsersHandler
{
    public function __construct(
        private readonly GroupInfoFetcher $groupInfoFetcher,
        private readonly GroupUsers $usersFetcher
    )
    {}

    public function handle(int $groupId): void
    {
        $group = Group::findOrFail($groupId);;

        // данные о группе из вк
        $groupInfo = $this->groupInfoFetcher->getGroupInfoById($group->vk_group_id);
        $users = $this->usersFetcher->fetch($groupInfo);
        if ($group->isNew()) {
            $group->initUsers($users);
            return;
        }

        $oldUsers = $group->userIds();
        $group
            ->subscribeUsers(array_diff($users, $oldUsers))
            ->unsubscribeUsers(array_diff($oldUsers, $users))
            ->replaceAllUsers($users);
    }
}
