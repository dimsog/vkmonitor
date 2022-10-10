<?php

declare(strict_types=1);

namespace App\Services\Settings;

use InvalidArgumentException;
use App\Models\VkToken;
use Illuminate\Support\Arr;

class SettingsStore
{
    public function store(array $settings)
    {
        if (!Arr::has($settings, 'tokens')) {
            throw new InvalidArgumentException('Не передан параметр tokens');
        }
        // 1) удаляем токены, которых уже нет
        $tokenIds = array_column($settings['tokens'], 'id');
        VkToken::deleteOldTokens($tokenIds);

        // 2) обновляем или сохраняем новые токены
        foreach ($settings['tokens'] as $token) {
            if ($token['id'] > 0) {
                VkToken::updateToken($token['id'], $token['client_id'], $token['access_token']);
            } else {
                VkToken::addToken($token['client_id'], $token['access_token']);
            }
        }
    }
}
