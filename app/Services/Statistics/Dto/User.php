<?php

declare(strict_types=1);

namespace App\Services\Statistics\Dto;

use App\Services\Vk\Dto\VkUser;

class User
{
    public function __construct(
        public readonly VkUser $vkUser,
        public readonly bool $subscribed
    )
    {}
}
