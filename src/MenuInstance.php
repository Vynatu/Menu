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

class MenuInstance
{
    protected $menu;

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
