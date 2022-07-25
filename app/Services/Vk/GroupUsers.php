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
        private readonly string $accessToken,
        private readonly VKApiClient $apiClient
    ) {}

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
            $response = $this->apiClient->groups()->getMembers($this->accessToken, [
                'group_id' => $groupInfo->id,
                'sort' => 'id_asc',
                'offset' => self::USERS_LIMIT * $i
            ]);
            $result = [...$result, ...$response['items']];
            sleep(1);
        }
        return $result;
    }

    private function calculateTotalPages(int $usersCount): int
    {
        return (int) ceil($usersCount / self::USERS_LIMIT);
    }
}
