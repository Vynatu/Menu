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

namespace Vynatu\Menu\Exceptions;


class NoSuchMenuFoundException extends \RuntimeException
{
    public function __construct($menuname, $message = "No such menu (:menu) was found", $code = 0, \Exception $previous = null)
    {
        parent::__construct(strtr($message, [':menu' => $menuname]), $code, $previous);
    }

}