<?php

declare(strict_types=1);

namespace Tests\Unit\App\Services\Statistics;

use App\Models\Group;
use App\Services\Statistics\DiffGroupUsersHandler;
use App\Services\Testing\GroupInfoFetcherMockFactory;
use App\Services\Vk\GroupInfoFetcher;
use App\Services\Vk\GroupUsers;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class DiffGroupUsersHandlerTest extends TestCase
{
    use RefreshDatabase;


    public function testHandle()
    {
        $groupId = 1;
        $group = Group::createByVkGroup($groupId);

        // новые пользователи
        $statisticHandler = $this->makeStatisticHandler(range(1, 100));
        $statisticHandler->handle($group);

        /** @var Group $group */
        $group = Group::findByVkGroupIdOrFail($groupId);

        $userIds = $group->userIds();
        $this->assertSame(range(1, 100), $userIds);

        // часть пользователей отписалась, часть подписалась
        $statisticHandler = $this->makeStatisticHandler($newUserIds = range(50, 105));
        $statisticHandler->handle($group);

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

    protected function makeStatisticHandler(array $userIds): DiffGroupUsersHandler
    {
        $this->instance(GroupUsers::class, $this->createUsersFetcherMock($userIds));
        $this->instance(GroupInfoFetcher::class, GroupInfoFetcherMockFactory::create($userIds));
        return $this->app->make(DiffGroupUsersHandler::class);
    }
}
