<?php
namespace Core\Http;

use Core\{Helpers\Container, Http\Query};

class Request implements \IteratorAggregate, \Countable {

    const METHOD_HEAD = 'HEAD';
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_PATCH = 'PATCH';
    const METHOD_DELETE = 'DELETE';
    const METHOD_PURGE = 'PURGE';
    const METHOD_OPTIONS = 'OPTIONS';
    const METHOD_TRACE = 'TRACE';
    const METHOD_CONNECT = 'CONNECT';

    protected static $instance;

    protected $uri;
    protected $path;
    protected $method;
    protected $query;
    protected $params;

    protected function __construct()
    {
        $this->uri = $_SERVER["REQUEST_URI"];
        $this->path = $_SERVER["PATH_INFO"] ?? '/';
        $this->method = $_SERVER["REQUEST_METHOD"] ?? self::METHOD_GET;
        $this->query = new Query($_SERVER["QUERY_STRING"] ?? '');
        $this->params = new Container(\array_merge( $_REQUEST, $_GET, $_POST ));
    }

    public function getInstance()
    {
        if(empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __get($key)
    {
        return $this->params->get($key);
    }

    public function method()
    {
        return \strtolower($this->method);
    }

    public function has($key)
    {
        return $this->params->has($key);
    }

    public function keys()
    {
        return $this->params->keys();
    }

    public function values()
    {
        return $this->params->values();
    }

    public function all()
    {
        return $this->params->all();
    }

    public function path()
    {
        return $this->path;
    }

    public function query()
    {
        return $this->query;
    }

    public function getIterator() {
        return $this->params->getIterator();
    }
    
    public function count()
    { 
        return $this->params->count(); 
    }
}