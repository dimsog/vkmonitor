<?php

declare(strict_types=1);

namespace Tests\Unit\App\Models;

use App\Models\Group;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupTest extends TestCase
{
    use RefreshDatabase;


    public function testIsNew()
    {
        $group = Group::createByVkGroup(1);
        $this->assertTrue($group->isNew());
        $group->subscribeUsers([1]);
        $this->assertFalse($group->isNew());
    }
}
