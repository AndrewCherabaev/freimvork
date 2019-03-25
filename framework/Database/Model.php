<?php
namespace Core\Database;

use Core\{Containers\Container, Database\QueryBuilder};

class Model {
    protected static $tablename = '';

    protected $primaryKey = 'id';

    /** @var Container $attributes */
    protected $attributes;
    
    /** @var QueryBuilder $queryBuilder */
    protected static $queryBuilder;

    public function __construct($attributes = null) 
    {
        $this->attributes = new Container();
        $this->fill($attributes);
        self::$queryBuilder = new QueryBuilder(static::$tablename);
    }

    public function __get($attribute)
    {
        return $this->attributes->get($attribute);
    }

    public static function getTableName()
    {
        return self::$tablename;
    }

    public static function query()
    {
        return self::$queryBuilder;
    }

    public function find($attributes)
    {
        $this->fill($attributes);
        return $this;
    }

    protected function fill($attributes)
    {
        if ($attributes) {
            if (is_array($attributes)) {
                $this->attributes->insert($attributes);
            } else {
                $this->attributes->set($this->primaryKey, $attributes);
            }
        }

        return $this;
    }

    public static function getInstance($attributes)
    {
        $instance = new static();
        return $instance->find($attributes);
    }
}