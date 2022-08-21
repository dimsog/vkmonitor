<?php

declare(strict_types=1);

namespace App\Services\Statistics;

use App\Models\Group;
use App\Models\GroupUserDiff;
use App\Services\Statistics\Dto\Diff;
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
        for ($i = self::LIMIT - 1; $i >= 0; $i--) {
            $diff = new Diff(new \DateTimeImmutable($startDate->clone()->addDays($i)->format('Y-m-d')));
            foreach ($users as $user) {
                if ($user->date == $diff->date) {
                    $vkUser = $vkUsers->where('id', $user->user_id)->first();
                    if (!empty($vkUser)) {
                        $diff->addUser(new User($vkUser, $user->subscribed));
                    }
                }
            }
            $result[] = $diff;
        }
        return collect($result);
    }
}
