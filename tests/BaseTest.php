<?php

class BaseTest extends PHPUnit_Framework_TestCase
{

    public function __construct()
    {
        $this->mock = \Mockery::mock('SimplexIS\VoicedataPhp\Voicedata')->makePartial();
        $this->mock->setConfig(new SimplexIS\VoicedataPhp\TestableConfig([]));
    }

    public function tearDown()
    {
        Mockery::close();
    }

    public function testCallExtensionNull()
    {
        $this->mock->shouldReceive('generateXml')->never();
        $this->assertFalse($this->mock->call(null, '+31123456789'));
    }

    public function testCallDestinationNull()
    {
        $this->mock->shouldReceive('generateXml')->never();
        $this->assertFalse($this->mock->call(101, null));
    }

    public function testCallExtensionDestinationNull()
    {
        $this->mock->shouldReceive('generateXml')->never();
        $this->assertFalse($this->mock->call(null, null));
    }

    public function testCallValidParameters()
    {
        $this->mock->shouldReceive('generateXml')
            ->once()
            ->andReturn(true);
        $this->mock->shouldReceive('doRequest')
            ->once()
            ->andReturn(true);
        $this->assertTrue($this->mock->call(101, '+31123456789'));
    }

    public function testGenerateXml()
    {
        $xml = $this->mock->generateXml(101, '+31123456789');
        $xml_object = simplexml_load_string($xml);
        
        $this->assertEquals((int) $xml_object->messagebody->extension, 101);
        $this->assertEquals((int) $xml_object->messagebody->destination, '+31123456789');
    }
}
