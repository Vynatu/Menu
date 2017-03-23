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


use Illuminate\Foundation\Application;
use Vynatu\Menu\Exceptions\NoSuchMenuFoundException;

class MenuManager
{
    protected $_app;
    protected $_menus = [];

    function __construct(Application $app)
    {
        $this->_app = $app;
    }

    public function register($name, $menu)
    {
        $this->_menus[$name] = $menu;
    }

    public function getMenu($menu)
    {
        return $this->get($menu);
    }

    public function get($menu)
    {
        if (! array_key_exists($menu, $this->_menus)) {
            throw new NoSuchMenuFoundException($menu);
        }

        return ($this->_app->make($menu)->generate());
    }
}