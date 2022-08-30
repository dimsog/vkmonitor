<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
