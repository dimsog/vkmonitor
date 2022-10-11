<?php

declare(strict_types=1);

namespace App\Services\Testing;

use App\Services\Vk\Dto\VkUser;
use App\Services\Vk\UsersFetcher;
use Mockery;
use Mockery\MockInterface;

class UsersFetcherMockFactory
{
    public static function create(array $userIds = []): MockInterface
    {
        return Mockery::mock(UsersFetcher::class, static function (MockInterface $mock) use ($userIds): void {
            $vkUsers = [];
            foreach ($userIds as $userId) {
                $vkUsers[] = new VkUser($userId, '', '', '', '');
            }
            $mock
                ->shouldReceive('fetchAllByIds')
                ->andReturn(collect($vkUsers));
        });
    }
}
