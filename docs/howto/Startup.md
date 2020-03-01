# How to start project

First of all, create `composer.json` file in your project directory. 

You can do it by doing `composer init` and asking questions. Or you can just create `composer.json` and fill it by yourself. **Important!** Do not add any repository to `"require"` field on this step!

Then you shoul add freimwork to `composer.json`. **freimvork** is not published in [Packagist](https://packagist.org/), so you shoud use `vcs` type of repository:
```json
{
  "require": {
    "php": "^7.1",
    "andrew/freimvork": "*"
  },
  "autoload": {
    "psr-4": {
      "App\\": "src/"
    }
  },
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/AndrewCherabaev/freimvork"
    }
  ]
}
```



If you did everything in proper way, after running
```
composer.install
```
you should see presented output:
```
Loading composer repositories with package information
Updating dependencies (including require-dev)      
Package operations: 1 install, 0 updates, 0 removals
  - Installing andrew/freimvork (0.0.1): Done
Writing lock file
Generating autoload files
```

Congratulations! You added **freimvork** and now can [make your app](./CreateApp.md)!
