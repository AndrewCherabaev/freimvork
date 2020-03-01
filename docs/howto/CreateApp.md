# How to create empty project

By default, **freimwork** starts with `index.php` file, placed in project root:
```php
require __DIR__ . '/vendor/autoload.php';

Freimvork\App::run();
```

Then you should create `src` folder, which contains project code:
```
src\
    config\
        database.php
        routes.php
    Http\
        Controllers
    Models\
    Views\
```

By default, `src\config\routes.php` should return empty array:
```php
return [];
```

When you can start your app by runnning dev-server
```
php -S localhost:8080
```
so you can see `not found` message in your browser. So, basic app structure is done.

All furthe instructions plased in [Manual](./../manual/README.md)