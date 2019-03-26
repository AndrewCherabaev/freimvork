<?php
namespace Core\Http;

use Core\Helpers\Container;

class Query {
    protected $container;

    public function __construct(string $queryString = '')
    {
        parse_str($queryString, $output);
        $this->container = new Container($output);        
    }

    public function has($key) 
    {
        return $this->container->has($key);
    }

    public function get($keystring)
    {
        return $this->container->get($keystring);
    }

    public function set($keystring, $value)
    {
        $this->container->set($keystring, $value);
        return $this;
    }

    public function insert(array $parameters = [])
    {
        $this->container->insert($parameters);
        return $this;
    }
} 