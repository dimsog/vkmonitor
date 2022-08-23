<?php

declare(strict_types=1);

namespace App\Services\Vk;

use App\Services\Vk\Dto\VkUser;
use Illuminate\Support\Collection;
use VK\Client\VKApiClient;

class UsersFetcher
{
    public function __construct(
        private readonly VKApiClient $vkApiClient
    )
    {}

    /**
     * @param Collection $vkUserIds
     * @param string $accessToken
     * @return Collection<VkUser>
     */
    public function fetchAllByIds(Collection $vkUserIds, string $accessToken): Collection
    {
        $result = [];
        foreach ($vkUserIds->chunk(500) as $users) {
            $response = $this->vkApiClient->users()->get($accessToken, [
                'user_ids' => $users->toArray(),
                'fields' => 'photo_200,screen_name'
            ]);
            foreach ($response as $rawUser) {
                $result[] = new VkUser(
                    $rawUser['id'],
                    $rawUser['first_name'],
                    $rawUser['last_name'],
                    $rawUser['screen_name'] ?? null,
                    $rawUser['photo_200'],
                    empty($rawUser['deactivated'])
                );
            }
        }
        return collect($result);
    }
}
