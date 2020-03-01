<?php
namespace Freimvork\Database;

use Freimvork\{Helpers\Container, Database\QueryBuilder};

class Model {
    protected static $tablename = '';

    protected static $primaryKey = 'id';

    /** @var Container $attributes */
    protected $attributes;

    public function __construct($attributes = null)
    {
        $this->attributes = new Container();
        $this->fill($attributes);
    }

    public function __get($attribute)
    {
        return $this->attributes->get($attribute);
    }

    public static function getTableName()
    {
        return static::$tablename;
    }

    public static function getPrimaryKey()
    {
        return static::$primaryKey;
    }

    public static function query()
    {
        return new QueryBuilder(new static);
    }

    public function find($attributes)
    {
        return $this->fill($attributes);
    }

    protected function fill($attributes)
    {
        if ($attributes) {
            if (\is_array($attributes)) {
                $this->attributes->insert($attributes);
            } else {
                $this->attributes->set(static::$primaryKey, $attributes);
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