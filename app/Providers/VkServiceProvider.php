<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Vk\GroupInfoFetcher;
use App\Services\Vk\UsersCountByGroup;
use App\Services\Vk\UsersFetcher;
use App\Services\Vk\Utils\GenerateVkAccessTokenLink;
use Illuminate\Support\ServiceProvider;
use VK\Client\VKApiClient;
use VK\OAuth\VKOAuth;

class VkServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(GenerateVkAccessTokenLink::class, function () {
            return new GenerateVkAccessTokenLink(config('services.vk.client_id'), new VKOAuth());
        });

        $this->app->bind(UsersCountByGroup::class, function () {
            return new UsersCountByGroup(
                config('services.vk.access_token'),
                $this->app->make(VKApiClient::class)
            );
        });

        $this->app->bind(UsersFetcher::class, function () {
            return new UsersFetcher(
                config('services.vk.access_token'),
                $this->app->make(UsersCountByGroup::class),
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