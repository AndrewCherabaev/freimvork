<?php
namespace Core\Database;

class QueryBuilder {
    protected $tablename;

    public function __construct(string $tablename)
    {
        $this->tablename = $tablename;
    }
}