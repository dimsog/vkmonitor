<?php

declare(strict_types=1);

namespace App\Services\Groups\Dto;

use App\Services\Vk\Dto\VkGroup;

class Group
{
    public function __construct(
        public readonly int $id,
        public readonly VkGroup $vkGroup
    ) {}
}
