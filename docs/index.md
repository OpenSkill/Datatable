# Introduction

**PLEASE NOTE**: This package is currently under development and not production ready yet!

OpenSkill/Datatable is a package for `laravel5` as well as all other composer based projects that provides a server side handler for a number of javascript table plugins.

Currently it supports the following frontend tables:

- [Datatables 1.9](http://legacy.datatables.net/)

## Features

`Datatable` has a number of features:

- Support `Collections`
- Easy to use interface with fully configurable behaviour
- Supports [Datatables 1.9](http://legacy.datatables.net/)
- Automatically generates html tables and the appropriate javascript on the side
- Fully tested
- Fully configurable
- Extensible

## Quickstart

### Composer
This package is available on [http://packagist.org](https://packagist.org/packages/chumper/datatable), just add it to your composer.json

```json
"openSkill/datatable": "0.1.1"
```

Alternatively, you can install it using the composer command:
```bash
composer require openSkill/datatable "0.1.1"
```

### Laravel 

The package is built with Laravel in mind, so just add the following lines to app.php

####Laravel 5
```php
    'providers' => [

        ...
        OpenSkill\Datatable\DatatableServiceProvider::class,
        ...
    ],
    'aliases' => [

    	...
        'Datatable'=> OpenSkill\Datatable\Facades\DatatableFacade::class,
    	...
    ],
```

To override the default configuration options you can publish the config file.
```bash
php artisan vendor:publish
```

**Cogratulation**, you are now able to use `Datatable` in your project. 

[Head on](basic-usage.md) to the next section 


