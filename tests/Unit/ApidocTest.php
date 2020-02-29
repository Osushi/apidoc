<?php

namespace Tests\Unit;

use Tests\TestCase;
use Osushi\Apidoc\Apidoc;

class ApidocTest extends TestCase
{
    /**
     * @test
     */
    public function testGetInstance()
    {
        $this->expectException(\Exception::class);
        $apiDoc = Apidoc::getInstance();
    }

    public function testInit()
    {
        $apiDoc = Apidoc::init();
        $this->assertTrue($apiDoc instanceof Apidoc);
    }

    public function testGetInstanceAfterInit()
    {
        $apiDoc = Apidoc::getInstance();
        $this->assertTrue($apiDoc instanceof Apidoc);
    }

    public function testCall()
    {
        $_SERVER['argv'] = [];
        $apiDoc = Apidoc::getInstance();
        try {
            $apiDoc->render();
            $this->assertTrue(true);
        } catch (\Exeption $e) {
            $this->fail('Unable to call method');
        }
    }
}
