# Model

Model is object mapping to batabase row

For now it has only constructor:
```php
$this->attributes = new Container();

if ($attributes) {
    if (is_array($attributes)) {
        $this->attributes->insert($attributes);
    } else {
        $this->attributes->set($this->primaryKey, $attributes);
    }
}
```
magic `__get` to retrieve property from `$attributes` property, and static `getTableName` method:
```php
return static::$tablename;
```
which uses `protected static $tablename` has to be defined in extending model's class.