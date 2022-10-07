<?php

namespace App\Services\Testing;

use Mockery;
use App\Services\Vk\Dto\VkGroup;
use App\Services\Vk\GroupInfoFetcher;
use Mockery\MockInterface;

class GroupInfoFetcherMockFactory
{
    public static function create(array $userIds = []): MockInterface
    {
        return Mockery::mock(GroupInfoFetcher::class, static function (MockInterface $mock) use ($userIds): void {
            $mock
                ->shouldReceive('getGroupInfoById')
                ->andReturn(new VkGroup(1, 'ApiClub', count($userIds), 'apiclub', '/path/to/photo'));

            $mock
                ->shouldReceive('users')
                ->andReturn($userIds);
        });
    }
}
