<?php

namespace Tests\Unit;

use Tests\TestCase;
use Osushi\Apidoc\Request;

class RequestTest extends TestCase
{
    private $request;

    public function setUp(): void
    {
        $this->request = new Request();
    }

    public function testConstruct()
    {
        $request = new Request([
            'method' => 'get',
            'path' => '/v1/users',
            'parameters' => [
                'param' => 'dummy',
            ],
            'headers' => [
                'Content-Type' => 'application/json'
            ],
        ]);

        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/v1/users', $request->getPath());
        $this->assertEquals([
            'param' => 'dummy',
        ], $request->getParameters());
        $this->assertEquals([
            'Content-Type' => 'application/json'
        ], $request->getHeaders());
    }

    public function testSetAndGetMethod()
    {
        $this->request->setMethod('post');
        $this->assertEquals('POST', $this->request->getMethod());
    }

    public function testSetAndGetPath()
    {
        $this->request->setPath('/users');
        $this->assertEquals('/users', $this->request->getPath());
    }

    public function testSetAndGetParameters()
    {
        $this->request->setParameters([
            'param' => 'dummy',
        ]);
        $this->assertEquals([
            'param' => 'dummy',
        ], $this->request->getParameters());
    }

    public function testSetAndGetHeaders()
    {
        $this->request->setHeaders([
            'Content-Type' => 'application/json'
        ]);
        $this->assertEquals([
            'Content-Type' => 'application/json'
        ], $this->request->getHeaders());
    }
}
