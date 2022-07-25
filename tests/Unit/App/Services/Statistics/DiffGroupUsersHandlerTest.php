<?php

declare(strict_types=1);

namespace Tests\Unit\App\Services\Statistics;

use App\Models\Group;
use App\Models\GroupUser;
use App\Models\GroupUserDiff;
use App\Services\Statistics\DiffGroupUsersHandler;
use App\Services\Vk\Dto\VkGroup;
use App\Services\Vk\GroupInfoFetcher;
use App\Services\Vk\GroupUsers;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class DiffGroupUsersHandlerTest extends TestCase
{
    use RefreshDatabase;


    public function setUp(): void
    {
        parent::setUp();
    }

    public function testHandle()
    {
        $groupId = 1;
        $group = Group::createByVkGroup($groupId);

        // новые пользователи
        $statisticHandler = $this->makeStatisticHandler(range(1, 100));
        $statisticHandler->handle($group->id);

        /** @var Group $group */
        $group = Group::findByVkGroupIdOrFail($groupId);

        $userIds = $group->userIds();
        $this->assertSame(range(1, 100), $userIds);

        // часть пользователей отписалась, часть подписалась
        $statisticHandler = $this->makeStatisticHandler($newUserIds = range(50, 105));
        $statisticHandler->handle($group->id);

        /** @var Group $group */
        $group = Group::findByVkGroupIdOrFail($groupId);
        $this->assertSame($newUserIds, $group->userIds());

        // проверим, кто за сегодня подписался и отписался
        $diffs = $group->usersDiff();
        $this->assertEqualsCanonicalizing(range(101, 105), $diffs[0]->subscribedUserIds);
        $this->assertEqualsCanonicalizing(range(1, 49), $diffs[0]->unsubscribedUserIds);
    }

    protected function createUsersFetcherMock(array $userIds): MockInterface
    {
        return $this->mock(GroupUsers::class, static function (MockInterface $mock) use ($userIds): void {
            $mock
                ->shouldReceive('fetch')
                ->andReturn($userIds);
        });
    }

    protected function createGroupInfoFetcherMock(int $usersCount): MockInterface
    {
        return $this->mock(GroupInfoFetcher::class, static function (MockInterface $mock) use ($usersCount): void {
            $mock
                ->shouldReceive('getGroupInfoById')
                ->andReturn(new VkGroup(1, 'ApiClub', $usersCount, 'apiclub', '/path/to/photo'));
        });
    }

    protected function makeStatisticHandler(array $userIds): DiffGroupUsersHandler
    {
        $this->instance(GroupUsers::class, $this->createUsersFetcherMock($userIds));
        $this->instance(GroupInfoFetcher::class, $this->createGroupInfoFetcherMock(count($userIds)));
        return $this->app->make(DiffGroupUsersHandler::class);
    }
}
