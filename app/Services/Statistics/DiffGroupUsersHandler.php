<?php

declare(strict_types=1);

namespace App\Services\Statistics;

use Throwable;
use App\Models\Group;
use App\Services\Telemetry\Telemetry;
use App\Services\Vk\GroupInfoFetcher;
use VK\Client\VKApiClient;

/**
 * Сервис занимается поиском подписавшихся и отписавшихся пользователей
 */
class DiffGroupUsersHandler
{
    public function __construct(
        private readonly GroupInfoFetcher $groupInfoFetcher
    )
    {
    }

    public function handle(Group $group): void
    {
        try {
            // данные о группе из вк
            $groupInfo = $this->groupInfoFetcher->getGroupInfoById($group->vk_group_id);
            $users = $this->groupInfoFetcher->users($groupInfo);

            if ($group->isNew()) {
                $group->initUsers($users);
                return;
            }

            $oldUsers = $group->userIds();
            $group->subscribeUsers(array_diff($users, $oldUsers));
            $group->unsubscribeUsers(array_diff($oldUsers, $users));

            $group->replaceAllUsers($users);
        } catch (Throwable $e) {
            Telemetry::exception($e);
            throw $e;
        }
    }
}
