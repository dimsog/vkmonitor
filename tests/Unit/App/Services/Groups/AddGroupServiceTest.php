<?php

declare(strict_types=1);

namespace Tests\Unit\App\Services\Groups;

use App\Models\User;
use App\Services\Groups\AddGroupService;
use App\Services\Testing\GroupInfoFetcherMockFactory;
use App\Services\Vk\GroupInfoFetcher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddGroupServiceTest extends TestCase
{
    use RefreshDatabase;

    private AddGroupService $addGroupService;


    public function setUp(): void
    {
        parent::setUp();
        $this->instance(GroupInfoFetcher::class, GroupInfoFetcherMockFactory::create());
        $this->addGroupService = app(AddGroupService::class);
    }

    public function testAdd()
    {
        $user = User::factory()->create();
        $group = $this->addGroupService->add(
            'https://vk.com/apiclub',
            $user->id
        );
        $this->assertTrue($group->id > 0);
        $this->assertEquals($group->vkGroup->screenName, 'apiclub');
    }
}
