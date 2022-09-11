<?php

declare(strict_types=1);

namespace App\Services\Vk;

use App\Models\VkToken as VkTokenModel;

class VkToken
{
    public function findActiveAccessToken(): string
    {
        return VkTokenModel::findActiveAccessToken();
    }
}
