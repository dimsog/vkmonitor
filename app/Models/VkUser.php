<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * App\Models\VkUser
 *
 * @method static \Illuminate\Database\Eloquent\Builder|VkUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VkUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VkUser query()
 * @mixin \Eloquent
 */
class VkUser extends Model
{
    use HasFactory;

    public $timestamps = false;


    public static function deleteUsersByGroup(int $groupId): int
    {
        return static::where('group_id', $groupId)
            ->delete();
    }

    public static function addUsers(int $groupId, array $users): void
    {
        $date = date('Y-m-d');
        foreach ($users as $userId) {
            static::insert([
                'date'      => $date,
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

    /**
     * @param int $groupId
     * @return int[]
     */
    public static function findUserIdsByGroupId(int $groupId): array
    {
        return static::where('group_id', $groupId)
            ->pluck('user_id')
            ->toArray();
    }
}
