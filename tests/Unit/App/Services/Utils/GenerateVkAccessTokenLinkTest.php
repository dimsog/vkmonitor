<?php

declare(strict_types=1);

namespace Tests\Unit\App\Services\Utils;

use App\Services\Vk\Utils\GenerateVkAccessTokenLink;
use Tests\TestCase;

class GenerateVkAccessTokenLinkTest extends TestCase
{
    private GenerateVkAccessTokenLink $generateVkAccessTokenLink;


    public function setUp(): void
    {
        parent::setUp();
        $this->generateVkAccessTokenLink = $this->app->make(GenerateVkAccessTokenLink::class);
    }

    public function testGenerateVkAccessTokenLink()
    {
        $this->assertEquals(
            "https://oauth.vk.com/authorize?client_id=". config('services.vk.client_id') ."&redirect_uri=https%3A%2F%2Foauth.vk.com%2Fblank.html&display=page&scope=327680&response_type=token&v=5.101",
            $this->generateVkAccessTokenLink->generate()
        );
    }
}
