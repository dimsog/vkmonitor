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


    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'members_count' => $this->membersCount,
            'screen_name' => $this->screenName,
            'photo_200' => $this->photo200
        ];
    }
}
