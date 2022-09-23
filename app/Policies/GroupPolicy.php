<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\GroupOwner;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy
{
    use HandlesAuthorization;

    public function delete(User $user, Group $group): bool
    {
        return GroupOwner::existsGroupAndUser($group->id, $user->id);
    }
}
