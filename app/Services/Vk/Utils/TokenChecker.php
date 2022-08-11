<?php

declare(strict_types=1);

namespace App\Services\Vk\Utils;

use VK\Client\VKApiClient;
use VK\Exceptions\VKApiException;

class TokenChecker
{
    public function __construct(
        private readonly VKApiClient $vkApiClient
    )
    {}

    public function check(string $token): bool
    {
        try {
            $this->vkApiClient->groups()->getById($token, [
                'group_id' => 'apiclub'
            ]);
            return true;
        } catch (VKApiException) {
            return false;
        }
    }
}
