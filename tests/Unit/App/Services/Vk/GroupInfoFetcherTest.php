<?php

declare(strict_types=1);

namespace Tests\Unit\App\Services\Vk;

use App\Services\Vk\GroupInfoFetcher;
use App\Services\Vk\VkToken;
use Mockery\MockInterface;
use Tests\TestCase;
use VK\Actions\Groups;
use VK\Client\VKApiClient;

class GroupInfoFetcherTest extends TestCase
{
    private GroupInfoFetcher $groupInfoFetcher;

    public function setUp(): void
    {
        parent::setUp();

        $mock = $this->mock(VKApiClient::class, function (MockInterface $mock): void {
            $mock->shouldReceive('groups')->andReturn(
                $this->mock(Groups::class, static function (MockInterface $mock): void {
                    $mock->shouldReceive('getById')->andReturn([
                       [
                           'id' => 1,
                           'members_count' => 1000,
                           'name' => 'ВКонтакте API',
                           'screen_name' => 'apiclub',
                           'photo_200' => '/path/to/photo'
                       ]
                    ]);
                })
            );
        });
        $this->instance(VKApiClient::class, $mock);

        $vkTokenMock = $this->mock(VkToken::class, function (MockInterface$mock): void {
            $mock->shouldReceive('findActiveAccessToken')
                ->andReturn('test');
        });
        $this->instance(VkToken::class, $vkTokenMock);

        $this->groupInfoFetcher = $this->app->make(GroupInfoFetcher::class);
    }

    public function testSuccessFetchGroupInfo()
    {
        $groupInfo = $this->groupInfoFetcher->getGroupInfoById(1);
        $this->assertEquals(1, $groupInfo->id);
        $this->assertEquals(1000, $groupInfo->membersCount);
        $this->assertEquals('ВКонтакте API', $groupInfo->name);
        $this->assertEquals('apiclub', $groupInfo->screenName);
        $this->assertEquals('/path/to/photo', $groupInfo->photo200);
    }
}
