<?php

declare(strict_types=1);

namespace App\Services\Vk\Utils;

use VK\OAuth\Scopes\VKOAuthUserScope;
use VK\OAuth\VKOAuth;
use VK\OAuth\VKOAuthDisplay;
use VK\OAuth\VKOAuthResponseType;

class GenerateVkAccessTokenLink
{
    public function __construct(
        private readonly int $clientId,
        private readonly VKOAuth $vkOAuth
    ) {}

    public function generate(): string
    {
        return $this->vkOAuth->getAuthorizeUrl(
            VKOAuthResponseType::TOKEN,
            $this->clientId,
            'https://oauth.vk.com/blank.html',
            VKOAuthDisplay::PAGE,
            [
                VKOAuthUserScope::OFFLINE,
                VKOAuthUserScope::GROUPS
            ]
        );
    }
}
