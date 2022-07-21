<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Vk\Utils\GenerateVkAccessTokenLink;
use Illuminate\Support\ServiceProvider;
use VK\OAuth\VKOAuth;

class VkServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(GenerateVkAccessTokenLink::class, function () {
            return new GenerateVkAccessTokenLink(config('services.vk.client_id'), new VKOAuth());
        });
    }
}
