<?php
namespace SimplexIS\VoicedataPhp;

class TestableConfig
{

    protected $config;

    /**
     * 
     * @param array $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * 
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        $this->config[$key] = $value;
    }

    /**
     * get config
     * 
     * @param string $key
     * @return mixed | NULL
     */
    public function get($key)
    {
        if (array_key_exists($key, $this->config)) {
            return $this->config[$key];
        } else {
            return null;
        }
    }
}
