<?php

declare(strict_types=1);

namespace App\Services\Vk;

use App\Services\Vk\Dto\VkGroup;
use RuntimeException;
use VK\Client\VKApiClient;

class GroupInfoFetcher
{
    private GroupUsers $groupUsers;


    public function __construct(
        private readonly string $accessToken,
        private readonly VKApiClient $vkApiClient,
    ) {
        $this->groupUsers = new GroupUsers($this->accessToken, $this->vkApiClient);
    }

    public function getGroupInfoById(int|string $groupId): VkGroup
    {
        $response = $this->vkApiClient->groups()->getById($this->accessToken, [
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

    public function users(VkGroup $groupInfo): array
    {
        return $this->groupUsers->fetch($groupInfo);
    }
}
