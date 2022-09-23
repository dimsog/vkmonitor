<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * App\Models\GroupOwner
 *
 * @method static \Illuminate\Database\Eloquent\Builder|GroupOwner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupOwner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupOwner query()
 * @mixin \Eloquent
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $group_id
 * @property int $user_id
 * @method static \Illuminate\Database\Eloquent\Builder|GroupOwner whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupOwner whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupOwner whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupOwner whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupOwner whereUserId($value)
 */
class GroupOwner extends Model
{
    use HasFactory;


    public static function assignGroupAndUser(int $groupId, int $userId): bool
    {
        $model = static::where('group_id', $groupId)
            ->where('user_id', $userId)
            ->first();

        if ($model !== null) {
            return true;
        }

        $model = new static();
        $model->group_id = $groupId;
        $model->user_id = $userId;
        return $model->save();
    }

    public static function existsGroupAndUser(int $groupId, int $userId): bool
    {
        return static::where('group_id', $groupId)
            ->where('user_id', $userId)
            ->exists();
    }

    public static function findAssignedGroupIds(int $userId): Collection
    {
        return static::where('user_id', $userId)
            ->pluck('group_id');
    }

    public static function deleteAssign(int $groupId, int $userId): void
    {
        static::where('group_id', $groupId)
            ->where('user_id', $userId)
            ->delete();
    }
}
