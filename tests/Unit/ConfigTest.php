<?php

namespace Tests\Unit;

use Tests\TestCase;
use Osushi\Apidoc\Config;

class ConfigTest extends TestCase
{
    private $confg;

    public function setUp(): void
    {
        $this->config = new Config();
    }

    public function testSetAndGetConfig()
    {
        $this->config->setConfig($this->config::OUTPUT_PATH, 'something');
        $actual = $this->config->getConfig($this->config::OUTPUT_PATH);
        $this->assertEquals('something', $actual);

        $this->assertFalse($this->config->getConfig('invalid'));
    }
}
