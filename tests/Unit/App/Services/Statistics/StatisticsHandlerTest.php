<?php

declare(strict_types=1);

namespace Tests\Unit\App\Services\Statistics;

use App\Models\VkUser;
use App\Models\VkUserDiff;
use App\Services\Statistics\StatisticHandler;
use App\Services\Vk\Dto\GroupInfo;
use App\Services\Vk\UsersFetcher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class StatisticsHandlerTest extends TestCase
{
    use RefreshDatabase;


    public function testHandle()
    {
        $groupInfo = new GroupInfo(
            1,
            'ApiClub',
            100,
            'api_club',
            '/path/to/photo200'
        );

        $statisticHandler = $this->makeStatisticHandler(range(1, 100));

        $this->assertFalse(VkUser::isExistVkGroup(1));
        $statisticHandler->handle($groupInfo);
        $this->assertTrue(VkUser::isExistVkGroup(1));

        $userIds = VkUser::findUserIdsByGroupId(1);
        $this->assertSame(range(1, 100), $userIds);

        // часть пользователей отписалась, часть подписалась
        $statisticHandler = $this->makeStatisticHandler($newUserIds = range(50, 105));
        $statisticHandler->handle($groupInfo);
        $userIds = VkUser::findUserIdsByGroupId(1);
        $this->assertSame($newUserIds, $userIds);

        // проверим, кто за сегодня подписался и отписался
        $diffs = VkUserDiff::findDiffByGroupId(1);
        $this->assertEqualsCanonicalizing(range(101, 105), $diffs[0]->subscribedUserIds);
        $this->assertEqualsCanonicalizing(range(1, 49), $diffs[0]->unsubscribedUserIds);
    }

    protected function createUsersFetcherMock(array $userIds): MockInterface
    {
        return $this->mock(UsersFetcher::class, static function (MockInterface $mock) use ($userIds): void {
            $mock
                ->shouldReceive('fetch')
                ->andReturn($userIds);
        });
    }

    protected function makeStatisticHandler(array $userIds): StatisticHandler
    {
        $this->instance(UsersFetcher::class, $this->createUsersFetcherMock($userIds));
        return $this->app->make(StatisticHandler::class);
    }
}
