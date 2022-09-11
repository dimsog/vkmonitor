<?php

declare(strict_types=1);

namespace App\Services\Vk;

use App\Services\Vk\Dto\VkGroup;
use VK\Client\VKApiClient;
use VK\Exceptions\Api\VKApiParamGroupIdException;
use VK\Exceptions\VKApiException;
use VK\Exceptions\VKClientException;

class GroupUsers
{
    private const USERS_LIMIT = 1000;


    public function __construct(
        private readonly VKApiClient $apiClient,
        private readonly VkToken $vkToken
    ) {
    }

    /**
     * @param int|string $groupId
     * @return array
     * @throws VKApiParamGroupIdException
     * @throws VKApiException
     * @throws VKClientException
     */
    public function fetch(VkGroup $groupInfo): array
    {
        $usersCount = $groupInfo->membersCount;
        $totalPages = $this->calculateTotalPages($usersCount);
        $result = [];
        for ($i = 0; $i < $totalPages; $i++) {
            $result = [...$result, ...$this->fetchUsers($groupInfo->id, $i)];
            if ($i % 3 == 0) {
                sleep(1);
            }
        }
        return $result;
    }

    private function calculateTotalPages(int $usersCount): int
    {
        return (int) ceil($usersCount / self::USERS_LIMIT);
    }

    private function fetchUsers(int $vkGroupId, int $page)
    {
        $response = $this->apiClient->groups()->getMembers($this->vkToken->findActiveAccessToken(), [
            'group_id' => $vkGroupId,
            'sort' => 'id_asc',
            'offset' => self::USERS_LIMIT * $page
        ]);
        return $response['items'];
    }
}
