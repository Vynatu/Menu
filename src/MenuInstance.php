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

/**
 * Used to create new menus or extend already existing ones. This class is instanciated only when needed.
 *
 * @package Vynatu\Menu
 */
abstract class MenuInstance
{
    protected $menu;

    public function generate()
    {
        throw new \RuntimeException('The generate function should be implemented');
    }

    public function getMenu()
    {
        return $this->menu;
    }

    public function setMenu(RootMenuItem $menu)
    {
        $this->menu = $menu;

        return $this;
    }
}
