Find the API documentation [here](https://menu.packages.vynatu.io/)

- [Introduction](#introduction)
  * [Installation](#installation)
- [Creating a Menu](#creating-a-menu)
  * [Setup the Service Provider](#setup-the-service-provider)
  * [Create the New Menu](#create-the-new-menu)
  * [Add items to the menu](#add-items-to-the-menu)
  * [Registering the menu](#registering-the-menu)
  * [Extending a menu](#extending-a-menu)
  * [Accessing menu items in a menu instance](#accessing-menu-items-in-a-menu-instance)
  * [Setting variables to a menu item](#setting-variables-to-a-menu-item)
- [Views](#views)
  * [Item iteration in views](#item-iteration-in-views)
- [Tips](#tips)

# Introduction
This package is yet another menu library. This library is different than the others because:
 
 - It is lazy loaded
 - Any menu is extendable
 - It is not loaded in a middleware
 - Each menu declaration is in it's own class
 
This structure makes it perfect to modularize and extend your menus through the usage of different module libraries the laravel framework can support.

## Installation
*Vynatu/Menu* only uses a single `service provider`.

*First, install with composer:*

```bash
composer require vynatu/menu
```

Then, add the service provider to `app.php`:

```php
<?php 

'providers' => [
    ...
    Vynatu\Menu\MenuServiceProvider::class,
]
```

>  Vynatu/Menu does not require an alias. You can call the menu manager directly using app('menu').

# Creating a Menu

## Setup the Service Provider

You can register menus in any service provider you use, but I prefer making a new service provider called `MenuServiceProvider`.

This allows you to better separate what the `AppServiceProvider` does and the new `MenuServiceProvider` that is solely used to register and extend menus.

*Create a new service provider (using Artisan):*

```bash
artisan make:provider MenuServiceProvider
```

Don’t forget to register it in the providers!:

```php
<?php 

'providers' => [
    ...
    App\Providers\MenuServiceProvider::class,
]
```

## Create the New Menu

Vynatu/Menu comes with a console command that lets you create menu classes very easily.

```bash
artisan make:menu MainMenu
```

> We suggest you put your Menus in a sub-folder (app/Menus).
  
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

> After creating your menu class, an instance of `\Vynatu\Menu\RootMenuItem` will automatically be injected in your `MenuInstance`.


## Add items to the menu

You can add items to the menu by using the `$this->menu` instance.

```php
<?php

function generate()
{
    $this->menu->add('Dashboard')->url('/home');                // Method 1
    $this->menu->add('My Account', '/');                        // Method 2
    $this->menu->add('List Users', 'route:users.list');         // Method 3
    $this->menu->add('Seperator')->section();                   // Method 4
    $this->menu->add('Edit Myself', 'route:users.edit|id:5');   // Method 5
    $this->menu->add('Logout')->route('auth.logout');           // Method 6
    $this->menu->add('Some Other Link', ['url' => '/link']);    // Method 7
    
}
```

| Method Number | What does `add()` return   | Additional Info                                                                                                                                                  |
|---------------|----------------------------|------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| 1             | A new `MenuItem`           | Sets the url to Dashboard                                                                                                                                        |
| 2             | The `$this->menu` instance | The route is set already by the second argument using an absolute URL. Useful for quick url assignment                                                           |
| 3             | The `$this->menu` instance | The route is associated using the `route:` prefix. Useful for quick route assignment                                                                             |
| 4             | A new `MenuItem`           | Useful to create a section. URLs or routes don't have to be set. When a function with no argument is called, a variable with the function's name is set to true. |
| 5             | The `$this->menu` instance | The route is associated with arguments, useful to create a quick route with parameters.                                                                          |
| 6             | A new `MenuItem`           | The route is assigned using the fluent interface, which makes the code look cleaner. You can also add an array of route parameters as the second argument.       |
| 7             | The `$this->menu` instance | You can pass an array as the second argument to immediately issue all the variables to the menu item, when you don't want to use the fluent interface.           |




## Registering the menu

In the menu service provider you have previously created, add this to the boot method:

```php
<?php

function boot(\Vynatu\Menu\MenuManager $menu)
{
    $menu->register('main_menu', \App\Menus\MainMenu::class);
}
```

> You don't have to use dependency injection. You can use `app('menu')->register(...)` instead.

## Extending a menu

Do the same steps as creating and registering a menu, but instead of using the register method:

```php
<?php

function boot(\Vynatu\Menu\MenuManager $menu)
{
    $menu->extend('main_menu', \App\Menus\MainMenuExtender::class);
}
```

## Accessing menu items in a menu instance

You can access any menu items in a menu instance this way:

```php
<?php

public function generate()
{
    $this->menu->management->add(...);
}
```

The `management` variable is created automatically when a new menu item is added. `snake_case` is used to create the variable name.

This means that if you created an item like `$this->menu->add('Management')`, a new variable called `management` will exist and can be accessed by the local menu instance or any menu extender.

If you use `_t` to create menu names (which means the slug name will always change), you can set the slug name statically:

```php
<?php

public function generate()
{
    $this->menu->add(_t('menus.management'))->route('admin.management')->slug('management');
}
```

This allow your other extenders to access the `management` menu item and, for example, change its icon:

```php
<?php

public function generate()
{
    $this->menu->management->icon('fa fa-home');
}
```

## Setting variables to a menu item

There are two ways to do this.

The first one, using a `function`. It is useful to conserve the `fluent` API:

```php
<?php

public function generate()
{
    $this->menu->management->icon('fa fa-home');
}
```

The second one, using a `direct variable assignment`:

```php
<?php

public function generate()
{
    $this->menu->management->icon = 'fa fa-home';
}
```

Everything can be changed in an extender, including the route and URL.


# Views

## Item iteration in views

**Here is an example using bootstrap navbar**:

**File Name: menus.main_menu**

```blade
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Some Menu Example</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                @include('menu::bootstrap.default', ['menu' => $menu])
            </ul>
        </div><!--/.nav-collapse -->
    </div><!--/.container-fluid -->
</nav>
```

**File Name: menu_elements** (Also available under `view('menu::bootstrap.default')`)

```blade
@foreach($menu->items() as $item)
    @if($item->hasSubItems())
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
               aria-expanded="false">{{ $item->title }}<span class="caret"></span></a>
            <ul class="dropdown-menu">
                @include('menu_elements', ['menu' => $item])
            </ul>
        </li>
    @else
        <li @if($item->active()) class="active" @endif>
            <a href="{{ $item->url }}">{{ $item->title }}</a>
        </li>
    @endif
@endforeach
```

This should achieve a menu looking like [the default bootstrap navbar](https://getbootstrap.com/examples/navbar/)


# Tips
- The `__construct` of your menu class works with dependency injection. Simply type hint the stuff you need in the parameter list and we'll ask Laravel to inject the stuff you need.