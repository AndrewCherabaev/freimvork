<?php
namespace Core\Helpers;

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
        return \array_keys($this->parameters);
    }

    public function values()
    {
        return \array_values($this->parameters);
    }

    public function has($keystring)
    {
        return \array_has($this->parameters, $keystring);
    }

    public function get($keystring, $default = null)
    {
        return \array_get($this->parameters, $keystring, $default);
    }

    public function set($keystring, $value = null)
    {
        \array_set($this->parameters, $keystring, $value);

        return $this;
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
        return \count($this->parameters); 
    }
}