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

/**
 * Class MenuManager
 * Adds, extends and generates menus
 *
 * @package Vynatu\Menu
 */
class MenuManager
{
    protected $_app;
    protected $_menus     = [];
    protected $_extenders = [];

    function __construct(Application $app)
    {
        $this->_app = $app;
    }

    /**
     * Registers a new menu in the menu container.
     *
     * @param $name
     * @param $menu
     */
    public function register($name, $menu)
    {
        $this->_menus[$name] = $menu;
    }

    /**
     * Registers a menu class extender in the container.
     * The class will be invoked when the original menu will be build.
     *
     * @param $name
     * @param $extender
     */
    public function extend($name, $extender)
    {
        if (! array_key_exists($this->_extenders[$name])) {
            $this->_extenders[$name] = [];
        }

        $this->_extenders[$name][] = $extender;
    }

    /**
     * @see MenuManager::get() Simple alias method
     *
     * @param $menu
     *
     * @return mixed
     */
    public function getMenu($menu)
    {
        return $this->get($menu);
    }

    /**
     * Gets the specific menu out of the menu container, builds it and calls extenders on it.
     *
     *
     * @param string $menu_name
     *
     * @return RootMenuItem
     */
    public function get($menu_name)
    {
        if (! array_key_exists($menu_name, $this->_menus)) {
            throw new NoSuchMenuFoundException($menu_name);
        }

        // Get the root menu and fetch it
        $instance = $this->_app->make($this->_menus[$menu_name]);
        $instance->setMenu(new RootMenuItem)
                 ->generate();

        $menu = $instance->getMenu();

        // Check if any extenders exist and execute them
        if (array_key_exists($menu_name, $this->_extenders)) {
            foreach ($this->_extenders[$menu_name] as $extender) {
                $extender_instance = $this->_app->make($extender);
                $extender_instance->setMenu($menu)
                                  ->generate();

                $menu = $extender_instance->getMenu();
            }
        }


        return $instance->getMenu();
    }
}