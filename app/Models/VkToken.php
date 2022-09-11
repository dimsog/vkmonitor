<?php

namespace App\Models;

use RuntimeException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\VkToken
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $last_access
 * @property int $client_id
 * @property string $access_token
 * @property int $active
 * @property string|null $error_text
 * @method static \Illuminate\Database\Eloquent\Builder|VkToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VkToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VkToken query()
 * @method static \Illuminate\Database\Eloquent\Builder|VkToken whereAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VkToken whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VkToken whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VkToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VkToken whereErrorText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VkToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VkToken whereLastAccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VkToken whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class VkToken extends Model
{
    use HasFactory;


    public static function deleteOldTokens(array $existTokenIds)
    {
        VkToken::whereNotIn('id', $existTokenIds)->delete();
    }

    public static function addToken(int $clientId, string $accessToken): bool
    {
        $model = new static();
        $model->client_id = $clientId;
        $model->access_token = $accessToken;
        return $model->save();
    }

    public static function updateToken(int $id, int $clientId, string $accessToken)
    {
        $model = static::find($id);
        if ($model === null) {
            return false;
        }
        $model->client_id = $clientId;
        $model->access_token = $accessToken;
        $model->save();
        return true;
    }

    public static function findActiveAccessToken(): string
    {
        $vkToken = static::where('active', 1)
            ->inRandomOrder()
            ->first();
        if (empty($vkToken)) {
            throw new RuntimeException('Access token не найден');
        }
        $vkToken->last_access = time();
        $vkToken->save();
        return $vkToken->access_token;
    }
}
