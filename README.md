## What is this package?
This package is yet another menu library. This library is different than the others because:
 - It is lazy loaded
 - Any menu is extendable
 - It is not loaded in a middleware
 - Each menu declaration is in it's own class
 
This structure makes it perfect to modularize and extend your menus through the usage of different module libraries the laravel framework can support.

## How should I install?
1. `composer require vynatu/menu`
2. Add `Vynatu\Menu\MenuServiceProvider::class,` to `config/app.php`


## How can I create a menu?
1. `artisan make:menu MainMenu`
2. Navigate to `app/Menus` and see your menu class created.
3. In any service provider you have, in the `boot` function, add:

```
function boot(\Vynatu\Menu\MenuManager $menu)
{
    $menu->register('main_menu', \App\Menus\MainMenu::class);
}
```

## How can I extend a menu?
// TODO