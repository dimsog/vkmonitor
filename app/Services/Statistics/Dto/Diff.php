<?php

declare(strict_types=1);

namespace App\Services\Statistics\Dto;

use DateTimeInterface;

class Diff
{
    /**
     * @param DateTimeInterface $date
     * @param User[] $users
     */
    public function __construct(
        public readonly DateTimeInterface $date,
        public array $users = []
    )
    {}

    public function addUser(User $user): self
    {
        $this->users[] = $user;
        return $this;
    }
}
