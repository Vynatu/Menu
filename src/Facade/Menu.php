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

namespace Vynatu\Menu\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * Optional facade you can add to the `aliases` section of `app.php`
 *
 * @package Vynatu\Menu\Facade
 */
class Menu extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'menu';
    }
}