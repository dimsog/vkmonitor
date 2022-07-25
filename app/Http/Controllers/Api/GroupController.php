<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ImportUsersFromGroup;
use App\Models\Group;
use App\Models\GroupUserDiff;
use App\Services\Vk\GroupInfoFetcher;

class GroupController extends Controller
{
    public final const STATUS_NEW_GROUP = 1;

    public final const STATUS_EXISTS_GROUP = 2;


    public function __construct(
        private readonly GroupInfoFetcher $groupInfoFetcher,
    )
    {}

    public function read(string $vkGroupId): array
    {
        $vkGroup = $this->groupInfoFetcher->getGroupInfoById($vkGroupId);
        $group = Group::findByVkGroup($vkGroup->id);
        if (empty($group)) {
            $group = Group::createByVkGroup($vkGroup->id);
            ImportUsersFromGroup::dispatch($group->id);
            return [
                'success' => true,
                'data' => [
                    'status' => self::STATUS_NEW_GROUP,
                    'users' => []
                ]
            ];
        }
        return [
            'success' => true,
            'data' => [
                'status' => self::STATUS_EXISTS_GROUP,
                'users' => $group->usersDiff()
            ]
        ];
    }
}
