<?php

declare(strict_types=1);

namespace Tests\Unit\App\Services;

use App\Services\Vk\UsersFetcher;
use Mockery\MockInterface;
use Tests\TestCase;
use VK\Actions\Groups;
use VK\Client\VKApiClient;

class UsersFetcherTest extends TestCase
{
    private UsersFetcher $usersFetcher;

    public function setUp(): void
    {
        parent::setUp();

        $mock = $this->mock(VKApiClient::class, function (MockInterface $mock): void {
            $mock->shouldReceive('groups')->andReturn(
                $this->mock(Groups::class, static function (MockInterface $mock): void {
                    $mock->shouldReceive('getMembers')->andReturn([
                        'count' => 2000,
                        'items' => range(1, 2000)
                    ]);

                    $mock->shouldReceive('getById')->andReturn([
                        [
                            'id' => 1,
                            'members_count' => 2000,
                            'name' => 'ВКонтакте API',
                            'screen_name' => 'apiclub',
                            'photo_200' => '/path/to/photo'
                        ]
                    ]);
                })
            );
        });
        $this->instance(VKApiClient::class, $mock);
        $this->usersFetcher = $this->app->make(UsersFetcher::class);
    }

    public function testUsers()
    {
        $this->assertEquals(range(1, 2000), $this->usersFetcher->fetch('apiclub'));
    }
}
