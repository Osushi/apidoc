<?php

namespace Tests\Unit;

use Tests\TestCase;
use Osushi\Apidoc\Response;

class ResponseTest extends TestCase
{
    private $response;

    public function setUp(): void
    {
        $this->response = new Response();
    }

    public function testConstruct()
    {
        $response = new Response([
            'code' => 200,
            'headers' => [
                'Content-Type' => 'application/json; charset=utf-8',
            ],
            'body' => '{"created_at": "2015-04-21T14:55:09.351Z"}',
        ]);

        $this->assertEquals(200, $response->getCode());
        $this->assertEquals([
            'Content-Type' => 'application/json; charset=utf-8',
        ], $response->getHeaders());
        $this->assertTrue(is_string($response->getBody()));
    }

    public function testSetAndGetCode()
    {
        $this->response->setCode(404);
        $this->assertEquals(404, $this->response->getCode());
    }

    public function testSetAndGetHeaders()
    {
        $this->response->setHeaders([
            'Content-Type' => 'application/json; charset=utf-8',
        ]);
        $this->assertEquals([
            'Content-Type' => 'application/json; charset=utf-8',
        ], $this->response->getHeaders());
    }

    public function testSetAndGetBody()
    {
        $this->response->setBody('{"created_at": "2015-04-21T14:55:09.351Z"}');
        $this->assertTrue(is_string($this->response->getBody()));
    }
}
