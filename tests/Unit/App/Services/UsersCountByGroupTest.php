<?php

declare(strict_types=1);

namespace Tests\Unit\App\Services;

use App\Services\Vk\UsersCountByGroup;
use Mockery\MockInterface;
use Tests\TestCase;
use VK\Actions\Groups;
use VK\Client\VKApiClient;

class UsersCountByGroupTest extends TestCase
{
    private UsersCountByGroup $usersCountByGroup;

    private UsersCountByGroup $failUsersCountByGroup;


    public function setUp(): void
    {
        parent::setUp();

        $mock = $this->mock(VKApiClient::class, function (MockInterface $mock): void {
            $mock->shouldReceive('groups')->andReturn(
                $this->mock(Groups::class, static function (MockInterface $mock): void {
                    $mock->shouldReceive('getMembers')->andReturn([
                        'count' => 100
                    ]);
                })
            );
        });
        $this->instance(VKApiClient::class, $mock);
        $this->usersCountByGroup = $this->app->make(UsersCountByGroup::class);

        $mock = $this->mock(VKApiClient::class, function (MockInterface $mock): void {
            $mock->shouldReceive('groups')->andReturn(
                $this->mock(Groups::class, static function (MockInterface $mock): void {
                    $mock->shouldReceive('getMembers')->andReturn([
                        'error' => null
                    ]);
                })
            );
        });
        $this->instance(VKApiClient::class, $mock);
        $this->failUsersCountByGroup = $this->app->make(UsersCountByGroup::class);
    }

    public function testSuccessFetchCount()
    {
        $this->assertEquals(100, $this->usersCountByGroup->count('test'));
    }

    public function testFailFetchCount()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Не удалось получить количество пользователей');
        $this->failUsersCountByGroup->count('test');
    }
}
