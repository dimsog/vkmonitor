<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Group
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $user_id
 * @property int $vk_group_id
 * @property int $vk_client_id
 * @property string $vk_access_token
 * @property-read Collection|\App\Models\GroupUser[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Group newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Group newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Group query()
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereVkAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereVkClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereVkGroupId($value)
 * @mixin \Eloquent
 */
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
