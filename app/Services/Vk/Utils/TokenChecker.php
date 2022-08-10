<?php

declare(strict_types=1);

namespace App\Services\Vk\Utils;

use VK\Client\VKApiClient;

class TokenChecker
{
    public function __construct(
        private readonly VKApiClient $vkApiClient
    )
    {}

    public function check(string $token): bool
    {
            $response = $this->vkApiClient->groups()->getById($token, [
                'group_id' => 'apiclub'
            ]);
            var_dump($response);
            return true;
    }
}
