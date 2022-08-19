<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Vk\GroupInfoFetcher;
use App\Services\Vk\GroupUsers;
use Illuminate\Support\ServiceProvider;
use VK\Client\VKApiClient;

class VkServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(GroupUsers::class, function () {
            return new GroupUsers(
                $this->app->make(VKApiClient::class),
                config('services.vk.access_token'),
            );
        });

        $this->app->bind(GroupInfoFetcher::class, function () {
            return new GroupInfoFetcher(
                $this->app->make(VKApiClient::class),
                config('services.vk.access_token'),
            );
        });
    }
}
