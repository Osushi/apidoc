<?php

namespace Tests\Unit;

use Tests\TestCase;
use Osushi\Apidoc\Parameter;
use Mockery as m;

class ParameterTest extends TestCase
{
    private $parameter;

    public function setUp()
    {
        $this->parameter = new Parameter();
    }
    
    public function testAddAndGetParameter()
    {
        $this->parameter->add('param1', ['required' => true]);
        $this->parameter->add('param2', []);
        $actual = $this->parameter->getParameter();
        $this->assertTrue(isset($actual['required']));
        $this->assertTrue(isset($actual['option']));
    }
    
    public function testNoteAndGetNote()
    {
        $this->parameter->note('note1');
        $this->parameter->note('note2');
        $actual = $this->parameter->getNote();
        $this->assertEquals(2, count($actual));
    }

    public function testValidate()
    {
        $mock = m::mock($this->parameter)
              ->makePartial()
              ->shouldAllowMockingProtectedMethods();

        // ISA
        $this->assertTrue($mock->validate($this->parameter::ISA, 'string'));
        try {
            $mock->validate($this->parameter::ISA, []);
            $this->fail('Failed validation ['. $this->parameter::ISA .']');
        } catch (\Exception $e) {
            $this->assertTrue(true);
        }
        
        // DEFAULT
        $this->assertTrue($mock->validate($this->parameter::DEFAULT, '10'));
        try {
            $mock->validate($this->parameter::DEFAULT, []);
            $this->fail('Failed validation ['. $this->parameter::DEFAULT .']');
        } catch (\Exception $e) {
            $this->assertTrue(true);
        }
        
        // REQUIRED
        $this->assertTrue($mock->validate($this->parameter::REQUIRED, true));
        try {
            $mock->validate($this->parameter::REQUIRED, 'invalid');
            $this->fail('Failed validation ['. $this->parameter::REQUIRED .']');
        } catch (\Exception $e) {
            $this->assertTrue(true);
        }
        
        // COMMENT
        $this->assertTrue($mock->validate($this->parameter::COMMENT, 'comment'));
        try {
            $mock->validate($this->parameter::COMMENT, []);
            $this->fail('Failed validation ['. $this->parameter::COMMENT .']');
        } catch (\Exception $e) {
            $this->assertTrue(true);
        }
        
        // EXCEPT
        $this->assertTrue($mock->validate($this->parameter::EXCEPT, []));
        try {
            $mock->validate($this->parameter::EXCEPT, 'invalid');
            $this->fail('Failed validation ['. $this->parameter::EXCEPT .']');
        } catch (\Exception $e) {
            $this->assertTrue(true);
        }
        
        // ONLY
        $this->assertTrue($mock->validate($this->parameter::ONLY, []));
        try {
            $mock->validate($this->parameter::ONLY, 'invalid');
            $this->fail('Failed validation ['. $this->parameter::ONLY .']');
        } catch (\Exception $e) {
            $this->assertTrue(true);
        }
        
        // default
        $this->assertFalse($mock->validate('invalid', []));
    }
    
    public function tearDown()
    {
        m::close();
    }
}
