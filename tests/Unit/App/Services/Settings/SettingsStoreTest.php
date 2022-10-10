<?php

declare(strict_types=1);

namespace Tests\Unit\App\Services\Settings;

use InvalidArgumentException;
use App\Services\Settings\SettingsReader;
use App\Services\Settings\SettingsStore;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsStoreTest extends TestCase
{
    use RefreshDatabase;

    private SettingsStore $settingsStore;


    public function setUp(): void
    {
        parent::setUp();
        $this->settingsStore = app(SettingsStore::class);
    }

    public function testEmptyArguments()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->settingsStore->store([]);
    }

    public function testStore(): void
    {
        $this->settingsStore->store([
            'tokens' => [
                [
                    'id' => 0,
                    'client_id' => 123,
                    'access_token' => 'test'
                ]
            ]
        ]);
        $settings = SettingsReader::read();
        $this->assertCount(1, $settings['tokens']);

        // обновим данные
        $this->settingsStore->store([
            'tokens' => [
                [
                    'id' => $settings['tokens'][0]->id,
                    'client_id' => $clientId = 1111,
                    'access_token' => $accessToken = 'access_token'
                ]
            ]
        ]);
        $settings = SettingsReader::read();
        $this->assertCount(1, $settings['tokens']);
        $this->assertEquals($clientId, $settings['tokens'][0]->client_id);
        $this->assertEquals($accessToken, $settings['tokens'][0]->access_token);

        // удалим данные
        $this->settingsStore->store([
            'tokens' => []
        ]);
        $settings = SettingsReader::read();
        $this->assertEmpty($settings['tokens']);
    }
}
