<?php
namespace SimplexIS\VoicedataPhp;

class TestableConfig
{

    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function set($key, $value)
    {
        $this->config[$key] = $value;
    }

    public function get($key)
    {
        if (array_key_exists($key, $this->config)) {
            return $this->config[$key];
        } else {
            return null;
        }
    }
}
