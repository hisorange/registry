<?php
namespace hisorange\Registry;

// Components.
use hisorange\Registry\Entity;
use hisorange\Registry\Manager;
// Interfaces.
use ArrayAccess;
use hisorange\Registry\Interfaces\Manager          as ManagerInterface;
// Execptions.
use hisorange\Registry\Exceptions\NamespaceMissing as NamespaceMissingException;

// PSR/LOG.
use Psr\Log\LoggerAwareInterface;

class Pool implements ArrayAccess/*, LoggerAwareInterface*/
{
    /**
     * The namespace used for the global instance.
     *
     * @var string
     */
    const GLOBAL_NAMESPACE       = 'global';

    /**
     * Store different instances of the entity manager.
     *
     * @var \hisorange\Registry\ManagerInterface|null
     */
    protected static $namespaces = [];

    /**
     * Check if the namespace is registered in the static container.
     *
     * @param  string $namespace
     * @return bool
     */
    public static function has($namespace)
    {
        return isset(static::$namespaces[$namespace]);
    }

    /**
     * Create a new named manager instance, or register an existing one.
     *
     * @param  string|null           $namespace When empty the global namespace is used.
     * @param  ManagerInterface|null $instance  When empty a new manager instance is used.
     * @return void
     */
    public static function set($namespace = null, ManagerInterface $instance = null)
    {
        static::$namespaces[$namespace ?: static::GLOBAL_NAMESPACE] = $instance ?: static::createNewManagerInstance();
    }

    /**
     * Get the manager under the given namespace.
     * When accessing the global namespace, it will be auto created if needed.
     * @throws \hisorange\Registry\Exceptions\NamespaceMissing
     *
     * @param  string|null $namespace When empty the global namespace is used.
     * @return \hisorange\Registry\Interfaces\Manager
     */
    public static function get($namespace = null)
    {
        // If the name is empty, replace it with the global string.
        if ($namespace === null) {
            $namespace = static::GLOBAL_NAMESPACE;
        }

        // Create the global instance.
        if ($namespace === static::GLOBAL_NAMESPACE and ! static::has(static::GLOBAL_NAMESPACE)) {
            static::set(static::GLOBAL_NAMESPACE);
        }

        if (static::has($namespace)) {
            return static::$namespaces[$namespace];
        } else {
            throw new NamespaceMissingException($namespace);
        }
    }

    /**
     * Delete a namespace if registered, throws exception if not registered.
     * @throws \hisorange\Registry\Exceptions\NamespaceMissing
     *
     * @param  string $namespace
     * @return void
     */
    public static function delete($namespace)
    {
        if (static::has($namespace)) {
            unset(static::$namespaces[$namespace]);
        } else {
            throw new NamespaceMissingException($namespace);
        }
    }

    /**
     * Easy way to hook into the manager's instance creation
     * if you wana use your custom manager class,
     * simply extend your custom Pool and overide this function.
     *
     * @return \hisorange\Registry\Interfaces\Manager
     */
    protected static function createNewManagerInstance()
    {
        return new Manager;
    }

    /**
     * Implementation to support the \ArrayAccess interface's isset($pool['foo']) syntax.
     *
     * @param  string $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return static::has($key);
    }

    /**
     * Implementation to support the \ArrayAccess interface's $pool['foo'] = $instance; syntax.
     *
     * @param  string $key
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        return static::set($key, $value);
    }

    /**
     * Implementation to support the \ArrayAccess interface's $bar = $pool['foo']; syntax.
     * @throws \hisorange\Registry\Exceptions\NamespaceMissing
     *
     * @param  string $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return static::get($key);
    }

    /**
     * Implementation to support the \ArrayAccess interface's unset($pool['foo']); syntax.
     * @throws \hisorange\Registry\Exceptions\NamespaceMissing
     *
     * @param  string $key
     * @return void
     */
    public function offsetUnset($key)
    {
        return static::delete($key);
    }
}