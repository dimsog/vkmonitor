<?php

declare(strict_types=1);

namespace App\Services\Statistics;

use App\Models\VkUser;
use App\Models\VkUserDiff;
use App\Services\Vk\Dto\GroupInfo;
use App\Services\Vk\UsersFetcher;

class StatisticHandler
{
    private readonly GroupExistsChecker $groupExistsChecker;


    public function __construct(
        private readonly UsersFetcher $usersFetcher
    )
    {
        $this->groupExistsChecker = new GroupExistsChecker();
    }

    public function handle(GroupInfo $groupInfo): void
    {
        $users = $this->usersFetcher->fetch($groupInfo->id);
        if (!$this->groupExistsChecker->isExists($groupInfo->id)) {
            VkUser::addUsers($groupInfo->id, $users);
            return;
        }

        $oldUsers = VkUser::findUserIdsByGroupId($groupInfo->id);

        // добавим всех подписавшихся пользователей с последней проверки
        foreach (array_diff($users, $oldUsers) as $userId) {
            VkUserDiff::addSubscribed($groupInfo->id, $userId);
        }

        // добавим всех отписавшихся пользователей с последней проверки
        foreach (array_diff($oldUsers, $users) as $userId) {
            VkUserDiff::addUnsubscribed($groupInfo->id, $userId);
        }

        VkUser::replaceUsersByGroup($groupInfo->id, $users);
    }
}
