<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ImportUsersFromGroup;
use App\Models\VkUser;
use App\Models\VkUserDiff;
use App\Services\Statistics\GroupExistsChecker;
use App\Services\Vk\GroupInfoFetcher;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public final const STATUS_NEW_GROUP = 1;

    public final const STATUS_EXISTS_GROUP = 2;


    public function __construct(
        private readonly GroupInfoFetcher $groupInfoFetcher,
        private readonly GroupExistsChecker $groupExistsChecker
    )
    {}

    public function read(string $groupId): array
    {
        $groupInfo = $this->groupInfoFetcher->getGroupInfoById($groupId);
        if (!$this->groupExistsChecker->isExists($groupInfo->id)) {
            ImportUsersFromGroup::dispatch($groupInfo->id);
            var_dump(123);
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
                'users' => VkUserDiff::findDiffByGroupId($groupInfo->id)
            ]
        ];
    }
}
