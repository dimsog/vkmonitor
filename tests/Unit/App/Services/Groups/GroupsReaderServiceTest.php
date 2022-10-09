<?php

declare(strict_types=1);

namespace Tests\Unit\App\Services\Groups;

use App\Models\User;
use App\Services\Groups\AddGroupService;
use App\Services\Groups\GroupsReaderService;
use App\Services\Testing\GroupInfoFetcherMockFactory;
use App\Services\Vk\GroupInfoFetcher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupsReaderServiceTest extends TestCase
{
    use RefreshDatabase;

    private GroupsReaderService $groupsReaderService;

    private AddGroupService $addGroupService;


    public function setUp(): void
    {
        parent::setUp();
        $this->instance(GroupInfoFetcher::class, GroupInfoFetcherMockFactory::create([1, 2, 3]));
        $this->groupsReaderService = app(GroupsReaderService::class);
        $this->addGroupService = app(AddGroupService::class);
    }

    public function testEmpty()
    {
        $user = User::factory()->create();
        $this->assertEmpty($this->groupsReaderService->read($user->id));
    }

    public function testNotEmpty()
    {
        $user = User::factory()->create();
        $group = $this->addGroupService->add('https://vk.com/apiclub', $user->id);

        $user2 = User::factory()->create();
        $this->addGroupService->add('https://vk.com/apiclub', $user2->id);

        $groups = $this->groupsReaderService->read($group->id);
        $this->assertCount(1, $groups);
    }
}
