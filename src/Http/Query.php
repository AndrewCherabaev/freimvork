<?php
/**
 * @author Me
 */
namespace Freimvork\Http;

use Freimvork\Helpers\Container;
/**
 * Query string converted into object
 * 
 * @package Query
 */
class Query {
    /**
     * Container for query fields
     *
     * @var Container
     */
    protected $container;

    /**
     * Creates Query instance
     *
     * @param string $queryString
     */
    public function __construct(string $queryString = '')
    {
        parse_str($queryString, $output);
        $this->container = new Container($output);        
    }

    /**
     * Cheks if query has $key
     *
     * @param string $key
     * @return boolean
     */
    public function has($key) 
    {
        return $this->container->has($key);
    }

    /**
     * Undocumented function
     *
     * @param string $keystring
     * @return void
     */
    public function get($keystring)
    {
        return $this->container->get($keystring);
    }

    /**
     * Undocumented function
     *
     * @param string $keystring Keys chain in 'key_1.key_2.etc' format
     * @param string $value
     * @return void
     */
    public function set($keystring, $value)
    {
        $this->container->set($keystring, $value);
        return $this;
    }

    /**
     * Inserts parameters in query
     *
     * @param array $parameters
     * Parameters as format where [key] is name of parameter and [value] is parameter value
     * @return void
     */
    public function insert(array $parameters = [])
    {
        $this->container->insert($parameters);
        return $this;
    }
} 