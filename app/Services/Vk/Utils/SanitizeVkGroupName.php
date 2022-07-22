<?php

declare(strict_types=1);

namespace App\Services\Vk\Utils;

class SanitizeVkGroupName
{
    public static function sanitize(string $groupLink): string
    {
        $groupLink = explode('?', trim($groupLink))[0];
        return str_replace([
            'https://vk.com/',
            'https://m.vk.com/',
            'http://vk.com/',
            'http://m.vk.com/',
            '/',
        ], '', $groupLink);
    }
}
