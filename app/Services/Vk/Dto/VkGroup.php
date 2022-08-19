<?php

declare(strict_types=1);

namespace App\Services\Vk\Dto;

class VkGroup
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly int $membersCount,
        public readonly string $screenName,
        public readonly string $photo200
    )
    {}
}
