<?php

declare(strict_types=1);

namespace App\Services\Statistics\Dto;

use App\Services\Calendar\WeekNames;
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

    public function getWeekName(): string
    {
        return WeekNames::getNameByWeekId($this->getWeekNumber());
    }

    public function getWeekNumber(): int
    {
        return (int) $this->date->format('N');
    }

    public function toArray(): array
    {
        return [
            'date' => $this->date->format('Y-m-d'),
            'weekName' => $this->getWeekName(),
            'weekNumber' => $this->getWeekNumber(),
            'users' => $this->users
        ];
    }
}
