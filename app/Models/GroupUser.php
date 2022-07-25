<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * App\Models\VkUser
 *
 * @method static \Illuminate\Database\Eloquent\Builder|GroupUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupUser query()
 * @mixin \Eloquent
 */
class GroupUser extends Model
{
    use HasFactory;

    protected $table = 'group_users';

    public $incrementing = false;


    public static function deleteUsersByGroup(int $groupId): int
    {
        return static::where('group_id', $groupId)
            ->delete();
    }

    public static function addUsers(int $groupId, array $users): void
    {
        foreach ($users as $userId) {
            static::insert([
                'group_id'  => $groupId,
                'user_id'   => $userId
            ]);
        }
    }

    public static function replaceUsersByGroup(int $groupId, array $users): void
    {
        static::deleteUsersByGroup($groupId);
        static::addUsers($groupId, $users);
    }
}
