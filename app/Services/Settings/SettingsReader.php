<?php

namespace App\Services\Settings;

use App\Models\VkToken;

class SettingsReader
{
    public static function read(): array
    {
        return [
            'tokens' => VkToken::all()
        ];
    }
}
