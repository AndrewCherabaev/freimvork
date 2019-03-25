<?php
namespace Core\Containers;

class QueryContainer {
    
    protected $parameters;

    public function __construct(array $props = [])
    {
        $this->parameters = $props;
    }

    public function all()
    {
        return $this->parameters;
    }

    public function insert(array $parameters = [])
    {
        foreach ($parameters as $key => $value) {
            $this->set($key, $value);
        }
        return $this;
    }

    public function has($keystring)
    {
        return array_has($this->parameters, $keystring);
    }

    public function get($keystring)
    {
        return array_get($this->parameters, $keystring);
    }

    public function set($keystring, $value = null)
    {
        array_set($this->parameters, $keystring, $value);

        return $this;
    }
}