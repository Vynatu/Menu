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


trait CreatesMenuItems
{
    /**
     * Adds a new underlying (submenu) MenuItem;
     *
     * @param string            $title  The title of your menu item. The slug will be assigned automatically via
     *                                  `snake_case` if you did not assign one using the next argument's array config
     *                                  or
     * @param string|array|null $config $config can either be a string, array or null. Each of those choices will have
     *                                  an impact on the returned data.
     *                                  - String (Always returns $this):
     *                                  1. Giving "/dashboard" will give it a direct URL (relative or not,
     *                                  depending if you preceed it with a /)
     *                                  2. Giving it "route:admin.dashboard" will resolve the route and assign it to
     *                                  the menu item's url. To add route parameters, you can do
     *                                  "route:admin.dashboard|url,1|user,2", which will create the route with the
     *                                  "url" param as "1" and "user" as "2"
     *                                  - Array (Always returns $this):
     *                                  1. By giving it an array, you directly specify the configuration and additional
     *                                  data to be passed to the menu item. This allows for quick configuration,
     *                                  rapidly, and directly onto the add function.
     *                                  2. To set the route, use the `route` key, for a url, use the `url` key.
     *                                  - Null (Always returns a new MenuInstance class):
     *                                  1. Normally, this is used for sub-menus. You can do something simply, like
     *                                  `$menu->add('Dashboard')->route('admin.dashboard')`
     *
     * @return \Vynatu\Menu\MenuItem
     */
    public function &add(string $title, $config = null)
    {
        $item = new MenuItem;
        $item->title = $title;
        $item->setParent($this);

        if ($config === null) {
            $slug = $item['__slug__'] = snake_case($title);
            $this->_items[$slug] = &$item;

            return $item;
        }

        if (is_array($config)) {
            $slug = $item['__slug__'] = ($config['name'] ?? snake_case($title));
            $this->_items[$slug] = &$item;

            $item->applyConfig($config);

            return $this;
        }

        // Else it's a string
        // Is it a route?
        if (starts_with($config, 'route:')) {
            $slug = $item['__slug__'] = snake_case($title);
            $this->_items[$slug] = &$item;

            $route = str_replace_first('route:', '', $config);
            $route_with_params = explode('|', $route);

            $route = $route_with_params[0];
            unset($route_with_params[0]);

            $params = [];
            foreach ($route_with_params as $param) {
                $_param = explode(',', $param);

                $params[$_param[0]] = $param[1];
            }

            $item->route($route, $params);

            return $this;
        }

        // Else it's a plain url
        $this->_items[snake_case($title)] = &$item;
        $item->url = $config;

        return $this;
    }
}