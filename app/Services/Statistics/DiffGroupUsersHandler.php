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
        private readonly VKApiClient $apiClient,
    )
    {
    }

    public function handle(Group $group): void
    {
        try {
            $groupInfoFetcher = new GroupInfoFetcher($this->apiClient, $group->vk_access_token);

            // данные о группе из вк
            $groupInfo = $groupInfoFetcher->getGroupInfoById($group->vk_group_id);
            $users = $groupInfoFetcher->users($groupInfo);

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
