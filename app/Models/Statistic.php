<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Statistic
 *
 * @property int $id
 * @property string $date
 * @property int $group_id
 * @property int $user_id
 * @property int $type
 * @property int $initial
 * @method static \Illuminate\Database\Eloquent\Builder|Statistic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Statistic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Statistic query()
 * @method static \Illuminate\Database\Eloquent\Builder|Statistic whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Statistic whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Statistic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Statistic whereInitial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Statistic whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Statistic whereUserId($value)
 * @mixin \Eloquent
 */
class Statistic extends Model
{
    use HasFactory;

    public const TYPE_USER_SUBSCRIBED = 1;

    public const TYPE_USER_UNSUBSCRIBED = -1;

    public $timestamps = false;


    public static function isExistVkGroup(int $groupId): bool
    {
        return static::where('group_id', $groupId)
            ->exists();
    }
}
