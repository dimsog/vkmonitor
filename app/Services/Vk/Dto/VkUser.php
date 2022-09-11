<?php

declare(strict_types=1);

namespace App\Services\Vk\Dto;

class VkUser
{
    public function __construct(
        public readonly int $id,
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly ?string $screenName,
        public readonly string $photo200,
        public readonly bool $deactivated = false
    )
    {}
}
