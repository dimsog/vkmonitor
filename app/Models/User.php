<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Socialite\Contracts\User as UserInterface;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $hidden = [
        'remember_token'
    ];

    public static function createOrUpdateFromSocialite(UserInterface $user): static
    {
        $model = static::where('vk_id', $user->getId())->first();
        if ($model == null) {
            $model = new static();
            $model->vk_id = $user->getId();
        }
        $model->name = $user->getName();
        $model->avatar = $user->getAvatar();
        $model->save();
        return $model;
    }
}
