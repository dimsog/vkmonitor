<?php

declare(strict_types=1);

namespace App\Services\Vk;

use VK\Client\VKApiClient;
use RuntimeException;

class UsersCountByGroup
{
    public function __construct(
        private readonly string $accessToken,
        private readonly VKApiClient $vkApiClient
    ) {}

    public function count(string $groupId): int
    {
        $response = $this->vkApiClient->groups()->getMembers($this->accessToken, [
            'group_id' => $groupId,
            'count' => 1
        ]);
        return $response['count'] ?? throw new RuntimeException('Не удалось получить количество пользователей');
    }
}
