<?php
namespace Core\Http;

use Core\Container;

class Request {

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

    protected $uri;
    protected $path;
    protected $method;
    protected $queryParams;
    protected $params;

    public function __construct()
    {
        $this->uri = $_SERVER["REQUEST_URI"] ?? '';
        $this->path = $_SERVER["PATH_INFO"] ?? '/';
        $this->method = $_SERVER["REQUEST_METHOD"] ?? self::METHOD_GET;
        $this->queryParams = $_SERVER["QUERY_STRING"] ?? '';
        $this->params = new Container(array_merge( $_REQUEST, $_GET, $_POST ));
    }

    public function __get($key)
    {
        return $this->params->get($key);
    }

    public function has($key)
    {
        return $this->params->has($key);
    }

    public function paramsKeys()
    {
        return $this->params->keys();
    }

    public function paramsValues()
    {
        return $this->params->values();
    }

    public function all()
    {
        return $this->params->all();
    }

    public function getPath()
    {
        return $this->path;
    }
}