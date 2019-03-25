<?php
namespace Core\Database;

use Core\{Container, Database\QueryBuilder};

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
        self::$queryBuilder = new QueryBuilder(static::$tablename);

        if ($attributes) {
            if (is_array($attributes)) {
                $this->attributes->insert($attributes);
            } else {
                $this->attributes->set($this->primaryKey, $attributes);
            }
        }
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
}