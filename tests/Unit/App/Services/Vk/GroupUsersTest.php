<?php

declare(strict_types=1);

namespace Tests\Unit\App\Services\Vk;

use App\Services\Vk\Dto\VkGroup;
use App\Services\Vk\GroupInfoFetcher;
use Mockery\MockInterface;
use Tests\TestCase;
use VK\Actions\Groups;
use VK\Client\VKApiClient;

class GroupUsersTest extends TestCase
{
    private GroupInfoFetcher $groupInfoFetcher;


    public function setUp(): void
    {
        parent::setUp();

        $mock = $this->mock(VKApiClient::class, function (MockInterface $mock): void {
            $mock->shouldReceive('groups')->andReturn(
                $this->mock(Groups::class, static function (MockInterface $mock): void {
                    $mock->shouldReceive('getMembers')->andReturn([
                        'count' => 2000,
                        'items' => range(1, 1000)
                    ]);
                })
            );
        });
        $this->instance(VKApiClient::class, $mock);
        $this->groupInfoFetcher = $this->app->make(GroupInfoFetcher::class);
    }

    public function testUsers()
    {
        $groupInfo = new VkGroup(1, 'apiclub', 2000, 'apiclub', '/path/to/photo');
        $this->assertEquals([
            ...range(1, 1000),
            ...range(1, 1000)
            ],
            $this->groupInfoFetcher->users($groupInfo)
        );
    }
}
