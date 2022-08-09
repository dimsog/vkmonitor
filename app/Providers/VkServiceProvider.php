<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Vk\GroupInfoFetcher;
use App\Services\Vk\GroupUsers;
use Illuminate\Support\ServiceProvider;
use VK\Client\VKApiClient;
use VK\OAuth\VKOAuth;

class VkServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(GroupUsers::class, function () {
            return new GroupUsers(
                config('services.vk.access_token'),
                $this->app->make(VKApiClient::class)
            );
        });

        $this->app->bind(GroupInfoFetcher::class, function () {
            return new GroupInfoFetcher(
                config('services.vk.access_token'),
                $this->app->make(VKApiClient::class)
            );
        });
    }
}
