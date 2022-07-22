<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    use HasFactory;

    public $timestamps = false;


    public static function isExistVkGroup(int $groupId): bool
    {
        return static::where('group_id', $groupId)
            ->exists();
    }
}
