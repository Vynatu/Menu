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


class MenuItem extends RootMenuItem
{
    /**
     * Sets the `url` parameter to the resolved value of $route
     *
     * @param string $route  The route to resolve
     * @param array  $params Additional parameters
     *
     * @return $this
     */
    public function route($route, array $params = [])
    {
        $this->url = $this->route($route, $params);

        return $this;
    }

    /**
     * Removes an item from the menu item configuration. Also works on sub-menus.
     *
     * @param string $slug
     *
     * @return $this
     */
    public function remove(string $slug)
    {
        unset($this->_items[$slug]);

        return $this;
    }

    /**
     * Creates an HTML Tag out of the item
     *
     * @param $item
     *
     * @return null|string
     */
    public function makeTag($item)
    {
        if (array_key_exists($item, $this->_items)) {
            return $item . '="' . $this->_items[$item] . '"';
        }

        return null;
    }

    public function slug($new_slug)
    {
        $old_name = $this->_items['__slug__'];

        $this->_items['__slug__'] = $new_slug;

        $this->_items['__parent__']->replaceItemName($old_name, $new_slug);

        return $this;
    }

    public function setParent($parent)
    {
        $this->_items['__parent__'] = $parent;

        return $this;
    }

    public function matches($m)
    {
        if (! isset($this->_items['_matches'])) {
            $this->_items['_matches'] = [];
        }

        $this->_items['_matches'][] = $m;

        return $this;
    }

    public function active()
    {
        if ($this->url == request()->path()) {
            return true;
        }

        if (! $this->_items['_matches']) {
            return false;
        }

        foreach ($this->_items['_matches'] as $match) {
            if (request()->route()->getName() == $match) {
                return true;
            }
        }

        return false;
    }

    /**
     * Sets a variable to a value (Direct assignement)
     *
     * @param $name
     * @param $value
     *
     */
    public function __set($name, $value)
    {
        $this->_items[$name] = $value;
    }

    /**
     * Sets a variable to a value (function chaining method)
     * If nothing is passed as parameter,
     *
     * @param $name
     * @param $arguments
     *
     * @return $this
     */
    public function __call($name, $arguments)
    {
        $this->_items[$name] = $arguments[0] ?? true;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function offsetSet($offset, $value)
    {
        $this->_items[$offset] = $value;
    }

    protected function applyConfig(array $config)
    {
        $this->_items += $config;

        return $this;
    }
}