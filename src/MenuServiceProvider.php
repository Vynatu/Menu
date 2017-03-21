<?php
/**
 *
 * This file is part of vynatu/menu.
 *
 * (c) 2017 Vynatu Cyberlabs, Inc. <felix@vynatu.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vynatu\Menu;


use Illuminate\Support\ServiceProvider;
use Vynatu\Menu\Console\MakeMenuCommand;

class MenuServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        // Expose commands
        $this->commands(MakeMenuCommand::class);

        // Expose Manager
        $this->app->singleton(
            MenuManager::class,
            function ($app) {
                return new MenuManager($app);
            }
        );

        $this->app->bind(
            MenuItem::class,
            function () {
                return new MenuItem;
            }
        );
    }

    public function provides()
    {
        return [MenuManager::class, MenuItem::class];
    }
}