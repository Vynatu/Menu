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
        if ($this->app->runningInConsole()) {
            $this->commands(MakeMenuCommand::class);

        }

        // Expose Manager
        $this->app->singleton(
            'menu',
            function ($app) {
                return new MenuManager($app);
            }
        );

        $this->app->alias('menu', MenuManager::class);

        // View Composer
        view()->share('menu', app('menu'));
    }

    public function provides()
    {
        return [MenuManager::class, 'menu'];
    }
}