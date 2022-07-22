<?php

declare(strict_types=1);

namespace App\Models\Dto;

use DateTimeImmutable;

final class VkUserDiffResponseItem
{
    public function __construct(
        public DateTimeImmutable $date,
        public array $subscribedUserIds = [],
        public array $unsubscribedUserIds = []
    ) {}
}
