---
title: Vynatu/Menu Documentation

language_tabs:
  - php

toc_footers:
  - <a href='/api'>API Reference</a>
  - <a href='#'>Back to Main Website</a>

search: true
---

# Introduction
Vynatu/Menu is a PHP (composer) package for Laravel. This package is different that the other menu packages because:

- It is lazy-loaded
- Any menu is extendable
- Is it not loaded in a middleware
- Each menu declaration is in it's own class


This structure makes it perfect to modularize and extend your menus through the usage of different module libraries the laravel framework can support.


# Installation

Vynatu/Menu only uses a `service provider`. 

> Installation with composer:

```php
composer require vynatu/menu
```

> Add the service provider to the `providers` array:

```php
<?php 

'providers' => [
    ...
    Vynatu\Menu\MenuServiceProvider::class,
]
```

<aside class="notice">
Vynatu/Menu does not require an alias. You can call the <b>menu manager</b> directly using <code>app('menu')</code>.
</aside>

# Creating a Menu

## Setup the Service Provider

You can register menus in any service provider you use, but I prefer making a new service provider called `MenuServiceProvider`. 

This allows me to better separate what the `AppServiceProvider` does and the new `MenuServiceProvider` that is solely used to register and extend menus.

> Create a new service provider (using Artisan):

```php
artisan make:provider MenuServiceProvider
```

> Don't forget to register it in the providers!

```php
<?php 

'providers' => [
    ...
    App\Providers\MenuServiceProvider::class,
]
```

## Create The New Menu

Vynatu/Menu comes with a console command that lets you create menu classes very easily.

```php
artisan make:menu MainMenu
```
I personally suggest you put your Menus in a sub-folder (`app/Menus`).
> Or you can make the class yourself:

```php
<?php
namespace App\Menus;

use Vynatu\Menu\MenuInstance;

class AdminMainMenu extends MenuInstance
{
    public function generate()
    {
        //
    }
}

```

After creating your menu class, an instance of `\Vynatu\Menu\RootMenuItem` should automatically be injected in your `MenuInstance`.


