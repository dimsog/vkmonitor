<?php

declare(strict_types=1);

namespace App\Services\Statistics;

use App\Models\Group;
use App\Models\GroupUserDiff;
use App\Services\Statistics\Dto\User;
use App\Services\Vk\UsersFetcher;
use Illuminate\Support\Collection;


class DiffUsersReaderService
{
    const LIMIT = 7;


    public function __construct(
        private readonly UsersFetcher $usersFetcher
    ) {}

    public function read(Group $group, int $page = 1): Collection
    {
        $startDate = now()->addDays(-$page * self::LIMIT);
        $endDate = $startDate->clone()->addDays(self::LIMIT);
        $startDate->addDays();
        $users = GroupUserDiff::findAllBetweenDates($group->id, $startDate, $endDate);
        $vkUsers = $this->usersFetcher->fetchAllByIds($users->pluck('user_id'), $group->vk_access_token);

        $result = [];
        foreach ($users as $user) {
            $vkUser = $vkUsers->where('id', $user->user_id)->first();
            if (!empty($vkUser)) {
                $result[] = new User($vkUser, $user->subscribed);
            }
        }
        return collect($result);
    }
}
