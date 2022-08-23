<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use HasFactory;


    public static function findByVkGroup(int $vkGroupId): ?static
    {
        return static::where([
            'vk_group_id' => $vkGroupId
        ])->first();
    }

    public static function findByVkGroupIdOrFail(int $vkGroupId): static
    {
        $group = static::findByVkGroup($vkGroupId);
        if (empty($group)) {
            throw new ModelNotFoundException();
        }
        return $group;
    }

    public static function existsByVkGroupId(int $vkGroupId): bool
    {
        return static::where('vk_group_id', $vkGroupId)
            ->exists();
    }

    public static function findGroupsByUser(int $userId): Collection
    {
        return static::where('user_id', $userId)
            ->orderByDesc('id')
            ->get();
    }

    public static function findAllGroups(): Collection
    {
        return static::all();
    }

    public static function createByVkGroup(int $vkGroupId): static
    {
        $model = new static();
        $model->vk_group_id = $vkGroupId;
        $model->save();
        return $model;
    }

    public function isNew(): bool
    {
        $usersExists = GroupUser::where('group_id', $this->id)->exists();
        $userDiffExists = GroupUserDiff::where('group_id', $this->id)->exists();

        return !$usersExists && !$userDiffExists;
    }

    public function subscribeUsers(array $userIds): self
    {
        foreach ($userIds as $userId) {
            GroupUserDiff::addSubscribed($this->id, $userId);
        }
        return $this;
    }

    public function unsubscribeUsers(array $userIds): self
    {
        foreach ($userIds as $userId) {
            GroupUserDiff::addUnsubscribed($this->id, $userId);
        }
        return $this;
    }

    public function initUsers(array $userIds): self
    {
        GroupUser::addUsers($this->id, $userIds);
        return $this;
    }

    public function replaceAllUsers(array $userIds): self
    {
        GroupUser::replaceUsersByGroup($this->id, $userIds);
        return $this;
    }

    public function users(): HasMany
    {
        return $this->hasMany(GroupUser::class);
    }

    public function userIds(): array
    {
        return array_column(\DB::select('
            SELECT user_id
            FROM group_users
            WHERE group_id = :group_id
        ', [
            ':group_id' => $this->id
        ]), 'user_id');
    }

    public function usersDiff(): array
    {
        return GroupUserDiff::findDiffByGroupId($this->id);
    }
}
