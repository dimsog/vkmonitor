<?php

declare(strict_types=1);

namespace App\Services\Vk;

use App\Services\Vk\Dto\GroupInfo;
use RuntimeException;
use App\Services\Vk\Utils\SanitizeVkGroupName;
use VK\Client\VKApiClient;
use VK\Exceptions\VKApiException;
use VK\Exceptions\VKClientException;

class GroupInfoFetcher
{
    public function __construct(
        private readonly string $accessToken,
        private readonly VKApiClient $vkApiClient
    ) {}

    /**
     * @param string $groupLink
     * @return int
     * @throws VKApiException
     * @throws VKClientException
     */
    public function getGroupInfoByLink(string $groupLink): GroupInfo
    {
        return $this->getGroupInfoById(SanitizeVkGroupName::sanitize($groupLink));
    }

    public function getGroupInfoById(int|string $groupId): GroupInfo
    {
        $response = $this->vkApiClient->groups()->getById($this->accessToken, [
            'group_id' => $groupId,
            'fields' => 'members_count'
        ]);
        if (empty($response[0]['id'])) {
            throw new RuntimeException('Группа не найдена');
        }
        return new GroupInfo(
            $response[0]['id'],
            $response[0]['name'],
            $response[0]['members_count'],
            $response[0]['screen_name'],
            $response[0]['photo_200']
        );
    }
}
