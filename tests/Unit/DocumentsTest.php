<?php

namespace Tests\Unit;

use Tests\TestCase;
use Osushi\Apidoc\Documents;
use Osushi\Apidoc\Parameter;
use Osushi\Apidoc\Config;
use Osushi\Apidoc\Request;
use Osushi\Apidoc\Response;
use Mockery as m;

class DocumentsTest extends TestCase
{
    private $documents;

    public function setUp()
    {
        $this->documents = new Documents(
            new Config()
        );
    }
         
    public function testRecord()
    {
        $parameter = new Parameter();
        $parameter->add('name', ['isa' => 'string', 'comment' => 'comment', 'except' => ['bob', 'john']]);

        $this->documents->record(
            'users:/v1/users:GET',
            $parameter,
            'comment'
        );

        $actual = $this->documents->getTable();
        $this->assertEquals(1, count($actual));
        $this->assertTrue(isset($actual['users']));
        $this->assertTrue(isset($actual['users']['/v1/users']));
        $this->assertTrue(isset($actual['users']['/v1/users']['GET']));
        $this->assertTrue(isset($actual['users']['/v1/users']['GET']['comment']) && $actual['users']['/v1/users']['GET']['comment'] === 'comment');
        $this->assertTrue(isset($actual['users']['/v1/users']['GET']['parameters']) && $actual['users']['/v1/users']['GET']['parameters'] instanceof Parameter);
        $this->assertTrue(empty($actual['users']['/v1/users']['GET']['examples']));
    }

    public function testExample()
    {
        $request = new Request([
            'method' => 'GET',
            'path' => '/v1/users',
            'parameters' => ['status' => 10, 'name' => 'tarou'],
            'headers' => ['Content-Type' => 'application/json'],
        ]);

        $response = new Response([
            'code' => 200,
            'headers' => ['Content-Type' => 'application/json; charset=utf-8'],
            'body' => '{"created_at": "2015-04-21T14:55:09.351Z"}',
        ]);

        $this->documents->example(
            'users:/v1/users:GET',
            $request,
            $response,
            'comment'
        );

        $actual = $this->documents->getTable();
        $this->assertEquals(1, count($actual));
        $this->assertTrue(isset($actual['users']));
        $this->assertTrue(isset($actual['users']['/v1/users']));
        $this->assertTrue(isset($actual['users']['/v1/users']['GET']));
        $this->assertTrue(isset($actual['users']['/v1/users']['GET']['examples']) && is_array($actual['users']['/v1/users']['GET']['examples']));
        $this->assertTrue(isset($actual['users']['/v1/users']['GET']['examples'][0]['comment']) && $actual['users']['/v1/users']['GET']['examples'][0]['comment'] === 'comment');
        $this->assertTrue(isset($actual['users']['/v1/users']['GET']['examples'][0]['request']) && $actual['users']['/v1/users']['GET']['examples'][0]['request'] instanceof Request);
        $this->assertTrue(isset($actual['users']['/v1/users']['GET']['examples'][0]['response']) && $actual['users']['/v1/users']['GET']['examples'][0]['response'] instanceof Response);
    }

    public function testLoad()
    {
        $mock = m::mock($this->documents)
              ->makePartial()
              ->shouldAllowMockingProtectedMethods();
        $this->assertTrue($mock->load() instanceof \Twig_Environment);
    }
    
    public function testGetKeys()
    {
        $mock = m::mock($this->documents)
              ->makePartial()
              ->shouldAllowMockingProtectedMethods();

        $actual = $mock->getKeys('users:/v1/users:GET');
        $this->assertTrue(is_array($actual));

        try {
            $actual = $mock->getKeys('invalid');
            $this->fail('Failed get keys');
        } catch (\Exception $e) {
            $this->assertTrue(true);
        }
    }
    
    public function testRender()
    {
        $this->assertFalse($this->documents->render());
        
        $_SERVER['argv'] = ['APIDOC'];
        $documents = new Documents(
            new Config([
                Config::OUTPUT_PATH => 'dummy',
            ])
        );
        try {
            $documents->render();
            $this->fail('Failed to occur exception');
        } catch (\Exception $e) {
            $this->assertTrue(true);
        }

        $config = new Config([
            Config::OUTPUT_PATH => 'examples',
        ]);
        $documents = new Documents($config);
        
        $parameter = new Parameter();
        $parameter->add('name', ['isa' => 'string', 'required' => true, 'comment' => 'user name', 'except' => ['bob', 'john']]);
        $parameter->add('status', ['isa' => 'numric', 'default' => '10', 'comment' => 'user status', 'only' => ['10', '20', '30']]);
        $parameter->note('Here is note1');
        $parameter->note('Here is note2');

        $documents->record(
            'users:/users:GET',
            $parameter,
            'Get All Users'
        );
        
        $request = new Request([
            'method' => 'GET',
            'path' => '/users',
            'parameters' => ['status' => 10, 'name' => 'tarou'],
            'headers' => ['Content-Type' => 'application/json'],
        ]);
        
        $response = new Response([
            'code' => 200,
            'headers' => ['Content-Type' => 'application/json; charset=utf-8'],
            'body' => '{"id": 1,"name": "tarou","status": 10,"created_at": "2015-04-21T14:55:09.351Z","updated_at": "2015-04-21T14:55:09.351Z"}',
        ]);
        
        $documents->example(
            'users:/users:GET',
            $request,
            $response,
            '200 Success'
        );
        
        $request = new Request([
            'method' => 'GET',
            'path' => '/users',
            'parameters' => ['status' => 20, 'name' => 'tarou'],
            'headers' => ['Content-Type' => 'application/json'],
        ]);
        
        $response = new Response([
            'code' => 404,
            'headers' => ['Content-Type' => 'application/json; charset=utf-8'],
            'body' => '{"message": "not found"}',
        ]);
        
        $documents->example(
            'users:/users:GET',
            $request,
            $response,
            '404 Not Found'
        );

        $parameter = new Parameter();
        $parameter->add('name', ['isa' => 'string', 'required' => true, 'comment' => 'user name', 'except' => ['bob', 'john']]);
        $parameter->add('status', ['isa' => 'numric', 'default' => '10', 'comment' => 'user status', 'only' => ['10', '20', '30']]);
        $parameter->note('Here is note1');
        $parameter->note('Here is note2');

        $documents->record(
            'users:/users/new:POST',
            $parameter,
            'Post New User'
        );
        
        $request = new Request([
            'method' => 'POST',
            'path' => '/users/new',
            'parameters' => ['status' => 10, 'name' => 'ichiro'],
            'headers' => ['Content-Type' => 'application/json'],
        ]);
        
        $response = new Response([
            'code' => 200,
            'headers' => ['Content-Type' => 'application/json; charset=utf-8'],
            'body' => '{"id": 1,"created_at": "2015-04-21T14:55:09.351Z"}',
        ]);
        
        $documents->example(
            'users:/users/new:POST',
            $request,
            $response,
            '200 Success'
        );

        $time = time();
        $documents->render();

        $path = realpath(__DIR__.'/../../'.$config->getConfig(Config::OUTPUT_PATH));
        $this->assertTrue(file_exists($path.'/toc.md') && filemtime($path.'/toc.md') >= $time);
        $this->assertTrue(file_exists($path.'/users.md') && filemtime($path.'/users.md') >= $time);
    }
    
    public function tearDown()
    {
        m::close();
    }
}
