<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\VkUser
 *
 * @method static \Illuminate\Database\Eloquent\Builder|GroupUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupUser query()
 * @mixin \Eloquent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $group_id
 * @property int $user_id
 * @method static \Illuminate\Database\Eloquent\Builder|GroupUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupUser whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupUser whereUserId($value)
 */
class GroupUser extends Model
{
    use HasFactory;

    protected $table = 'group_users';

    public $incrementing = false;

    public $timestamps = true;


    public static function deleteUsersByGroup(int $groupId): int
    {
        return static::where('group_id', $groupId)
            ->delete();
    }

    public static function addUsers(int $groupId, array $users): void
    {
        foreach (collect($users)->chunk(1000) as $userIds) {
            $batchedUsers = [];
            foreach ($userIds as $userId) {
                $batchedUsers[] = [
                    'created_at' => now(),
                    'group_id' => $groupId,
                    'user_id' => $userId
                ];
            }
            static::insert($batchedUsers);
        }
    }

    public static function replaceUsersByGroup(int $groupId, array $users): void
    {
        static::deleteUsersByGroup($groupId);
        static::addUsers($groupId, $users);
    }
}
