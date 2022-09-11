<?php

declare(strict_types=1);

namespace App\Models;

use DateTimeInterface;
use App\Models\Dto\GroupUserDiffItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\VkUserDiff
 *
 * @method static \Illuminate\Database\Eloquent\Builder|GroupUserDiff newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupUserDiff newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupUserDiff query()
 * @mixin \Eloquent
 * @property int $id
 * @property \Illuminate\Support\Carbon $date
 * @property int $group_id
 * @property int $user_id
 * @property bool $subscribed
 * @method static \Illuminate\Database\Eloquent\Builder|GroupUserDiff whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupUserDiff whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupUserDiff whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupUserDiff whereSubscribed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupUserDiff whereUserId($value)
 */
class GroupUserDiff extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'group_users_diff';

    public $incrementing = false;


    protected $casts = [
        'subscribed' => 'boolean',
        'date' => 'date'
    ];

    public static function addSubscribed(int $groupId, int $userId): void
    {
        static::subscribeOrUnsubscribe($groupId, $userId, true);
    }

    public static function addUnsubscribed(int $groupId, int $userId): void
    {
        static::subscribeOrUnsubscribe($groupId, $userId, false);
    }

    /**
     * @param int $groupId
     * @return GroupUserDiffItem[]
     * @throws \Exception
     */
    public static function findDiffByGroupId(int $groupId): array
    {
        $users = static::where('group_id', $groupId)
            ->orderByDesc('date')
            ->get();
        $result = [];
        if ($users->isEmpty()) {
            return [];
        }
        $currentDate = $users[0]->date;
        $subscribedUsers = [];
        $unsubscribedUsers = [];
        foreach ($users as $user) {
            /** @var GroupUserDiff $user */
            if ($user->date != $currentDate) {
                $result[] = new GroupUserDiffItem(
                    new \DateTimeImmutable($currentDate->format('Y-m-d')),
                    $subscribedUsers,
                    $unsubscribedUsers
                );
                $currentDate = $user->date;
                $subscribedUsers = [];
                $unsubscribedUsers = [];
            } else {
                if ($user->subscribed == 1) {
                    $subscribedUsers[] = $user->user_id;
                } else {
                    $unsubscribedUsers[] = $user->user_id;
                }
            }
        }
        if (!empty($subscribedUsers) || !empty($unsubscribedUsers)) {
            $result[] = new GroupUserDiffItem(
                new \DateTimeImmutable($currentDate->format('Y-m-d')),
                $subscribedUsers,
                $unsubscribedUsers
            );
        }
        return $result;
    }

    private static function subscribeOrUnsubscribe(int $groupId, int $userId, bool $subscribe): void
    {
        $date = date('Y-m-d');
        $user = static::where('date', $date)
            ->where('group_id', $groupId)
            ->where('user_id', $userId)
            ->where('subscribed', !$subscribe)
            ->first();

        // смысл блока в том, что если пользователь подписался, а сейчас отписался,
        // сделаем так, чтобы этот пользователь не дублировался
        if ($user !== null) {
            $user->subscribed = $subscribe;
            $user->save();
            return;
        }

        static::insert([
            'date'      => $date,
            'group_id'  => $groupId,
            'user_id'   => $userId,
            'subscribed' => $subscribe
        ]);
    }

    public static function findAllBetweenDates(int $groupId, DateTimeInterface $startDate, DateTimeInterface $endDate): Collection
    {
        return static::where('group_id', $groupId)
            ->where('date', '>=', $startDate)
            ->where('date', '<=', $endDate)
            ->orderByDesc('date')
            ->orderByDesc('subscribed')
            ->get();
    }
}
