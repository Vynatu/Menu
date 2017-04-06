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


use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Traversable;

class RootMenuItem implements \ArrayAccess, Arrayable, \Countable, \IteratorAggregate, Jsonable, \JsonSerializable
{
    use CreatesMenuItems;

    protected $_items = [];

    public function items()
    {
        $items = [];

        foreach ($this->_items as $key => $item) {
            if ($item instanceof MenuItem) {
                $items[$key] = $item;
            }
        }

        return $items;
    }

    public function hasSubItems()
    {
        foreach ($this->_items as $key => $item) {
            if ($item instanceof MenuItem) {
                return true;
            }
        }

        return false;
    }

    public function replaceItemName($old, $new)
    {
        $keys = array_keys($this->_items);

        if (($index = array_search($old, $keys)) === false) {
            return $this;
        }

        $keys[$index] = $new;

        $this->_items = array_combine($keys, array_values($this->_items));

        return $this;
    }

    /**
     * Gets a submenu item using magic
     *
     * @param $name
     *
     * @return mixed|null
     */
    public function __get($name)
    {
        return $this->_items[$name] ?? null;
    }

    /**
     * Retrieve an external iterator
     *
     * @link  http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     *        <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->_items);
    }

    /**
     * Whether a offset exists
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset <p>
     *                      An offset to check for.
     *                      </p>
     *
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->_items);
    }

    /**
     * Offset to retrieve
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param mixed $offset <p>
     *                      The offset to retrieve.
     *                      </p>
     *
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->_items[$offset];
    }

    /**
     * Offset to set
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $value  <p>
     *                      The value to set.
     *                      </p>
     *
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        // Do Nothing, because setting stuff manually is disabled for root item menus
    }

    /**
     * Offset to unset
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param mixed $offset <p>
     *                      The offset to unset.
     *                      </p>
     *
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        unset($this->_items[$offset]);
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int $options
     *
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->_items, $options);
    }

    /**
     * Count elements of an object
     *
     * @link  http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     *        </p>
     *        <p>
     *        The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return count($this->_items);
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     *        which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        return $this->toArray();
    }

    public function toArray()
    {
        return array_map(
            function ($v) {
                return $v instanceof Arrayable ? $v->toArray() : $v;
            },
            $this->_items
        );
    }
}