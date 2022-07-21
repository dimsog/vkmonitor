<?php

namespace Tests\Unit\App\Services\Utils;

use App\Services\Vk\Utils\SanitizeVkGroupName;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function testSanitize()
    {
        $this->assertEquals('demo', SanitizeVkGroupName::sanitize('https://vk.com/demo'));
        $this->assertEquals('demo', SanitizeVkGroupName::sanitize('http://vk.com/demo'));
        $this->assertEquals('demo', SanitizeVkGroupName::sanitize('https://m.vk.com/demo'));
        $this->assertEquals('demo', SanitizeVkGroupName::sanitize('http://m.vk.com/demo'));
        $this->assertEquals('demo', SanitizeVkGroupName::sanitize('http://m.vk.com/demo/?test=1'));
    }
}
