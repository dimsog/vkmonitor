<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Dto\VkUserDiffResponseItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\VkUserDiff
 *
 * @method static \Illuminate\Database\Eloquent\Builder|VkUserDiff newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VkUserDiff newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VkUserDiff query()
 * @mixin \Eloquent
 */
class VkUserDiff extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $casts = [
        'subscribe' => 'boolean'
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
     * @return VkUserDiffResponseItem[]
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
            /** @var VkUserDiff $user */
            if ($user->date != $currentDate) {
                $result[] = new VkUserDiffResponseItem(
                    new \DateTimeImmutable($currentDate),
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
            $result[] = new VkUserDiffResponseItem(
                new \DateTimeImmutable($currentDate),
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
            $user->subscribe = $subscribe;
            $user->save();
        }

        static::insert([
            'date'      => $date,
            'group_id'  => $groupId,
            'user_id'   => $userId,
            'subscribed' => $subscribe
        ]);
    }
}
