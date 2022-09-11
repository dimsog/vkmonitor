<?php

declare(strict_types=1);

namespace App\Services\Vk;

use App\Models\VkToken;
use App\Services\Vk\Dto\VkGroup;
use Illuminate\Support\Collection;
use RuntimeException;
use VK\Client\VKApiClient;

class GroupInfoFetcher
{
    public function __construct(
        private readonly VKApiClient $apiClient,
        private readonly GroupUsers $groupUsers
    )
    {
    }

    /**
     * @param int|string $groupId
     * @return VkGroup
     * @throws \VK\Exceptions\VKApiException
     * @throws \VK\Exceptions\VKClientException
     */
    public function getGroupInfoById(int|string $groupId): VkGroup
    {
        $response = $this->apiClient->groups()->getById(VkToken::findActiveAccessToken(), [
            'group_id' => $groupId,
            'fields' => 'members_count'
        ]);
        if (empty($response[0]['id'])) {
            throw new RuntimeException('Группа не найдена');
        }
        return new VkGroup(
            $response[0]['id'],
            $response[0]['name'],
            $response[0]['members_count'],
            $response[0]['screen_name'],
            $response[0]['photo_200']
        );
    }

    public function getGroupInfoByIds(array $groupIds): Collection
    {
        $response = $this->apiClient->groups()->getById(VkToken::findActiveAccessToken(), [
            'group_ids' => $groupIds,
            'fields' => 'members_count'
        ]);
        return collect(array_map(static function ($item) {
            return new VkGroup(
                $item['id'],
                $item['name'],
                $item['members_count'],
                $item['screen_name'],
                $item['photo_200']
            );
        }, $response));
    }

    public function users(VkGroup $groupInfo): array
    {
        return $this->groupUsers->fetch($groupInfo);
    }
}
