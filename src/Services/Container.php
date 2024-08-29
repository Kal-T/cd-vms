<?php

namespace App\Services;

class Container
{
    private $factories = [];
    private $instances = [];

    public function set($name, callable $factory)
    {
        $this->factories[$name] = $factory;
    }

    public function get($name)
    {
        if (!isset($this->instances[$name])) {
            if (!isset($this->factories[$name])) {
                throw new \Exception("Service not found: " . $name);
            }
            // Create the service using the factory
            $this->instances[$name] = $this->factories[$name]();
        }
        return $this->instances[$name];
    }
}
