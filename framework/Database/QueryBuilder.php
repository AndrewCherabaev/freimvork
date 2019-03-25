<?php
namespace Core\Database;

class QueryBuilder {
    protected $tablename;

    protected $select;
    protected $where;

    public function __construct(string $tablename)
    {
        $this->tablename = $tablename;
    }

    public function select($columns = ['*'])
    {
        $this->select = (is_array($columns)) ? $columns : func_get_args();
        return $this;
    }

    public function where($key, $op, $value)
    {
        if (is_array($key) && empty($op) && empty($value)) {
            $this->where = array_map(function($where){
                return self::compileWhere($where[0], $where[1], $where[2] ?? null);
            }, $key);
            return $this;
        }

        $this->where = [
            self::compileWhere($key, $op, $value)
        ];
        return $this;
    }

    protected static function compileWhere($key, $op, $value)
    {
        if ($op && !$value){
            list($value, $op) = [$op, '='];
        }

        return [$key, $op, $value];
    }

    public function toSql()
    {
        if (!$this->select) $this->select();
        $columns = implode(',', $this->select);
        $where = null;
        if ($this->where) {
            $where = "WHERE " . implode(',', array_map(function($condition){
                return implode('', $condition);
            }, $this->where));
        }
        return "SELECT {$columns} FROM {$this->tablename} {$where}";
    }

    public function get()
    {

    }
}