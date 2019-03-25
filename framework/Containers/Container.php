<?php
namespace Core\Containers;

class Container implements \IteratorAggregate, \Countable {

    protected $parameters = [];

    public function __construct($parameters = [])
    {
        $this->parameters = $parameters;
    }

    public function all()
    {
        return $this->parameters;
    }

    public function keys()
    {
        return array_keys($this->parameters);
    }

    public function values()
    {
        return array_values($this->parameters);
    }

    public function get($key, $default = null)
    {
        return $this->has($key) ? $this->parameters[$key] : $default;
    }

    public function set($key, $value)
    {
        $this->parameters[$key] = $value;
        return $this;
    }

    public function has($key)
    {
        return array_key_exists($key, $this->parameters);
    }

    public function insert(array $parameters = [])
    {
        foreach ($parameters as $key => $value) {
            $this->set($key, $value);
        }
        return $this;
    }

    public function getIterator() {
        return new \ArrayIterator($this->parameters);
    }
    
    public function count()
    { 
        return count($this->parameters); 
    }
}