<?php
namespace hisorange\Registry;

// Interfaces.
use ArrayAccess;
use hisorange\Registry\Interfaces\Entity        as EntityInterface;
use hisorange\Registry\Interfaces\Manager       as ManagerInterface;
// Execptions.
use hisorange\Registry\Exceptions\EntityMissing as EntityMissingException;

class Manager implements ArrayAccess, ManagerInterface
{
    /**
     * Store the entities under their keys.
     *
     * @var array
     */
    protected $registry = [];

    /**
     * {@inheritdocs}
     */
    public function registerAsGlobal()
    {
        Pool::set(Pool::GLOBAL_NAMESPACE, $this);
    }

    /**
     * {@inheritdocs}
     */
    public function has($key)
    {
        return isset($this->registry[$key]);
    }

    /**
     * {@inheritdocs}
     */
    public function get($key, $fallback = null)
    {
        return $this->has($key) ? $this->registry[$key]->getValue() : $fallback;
    }

    /**
     * {@inheritdocs}
     */
    public function set($key, $value)
    {
        if ( ! $this->has($key)) {
            $this->registry[$key] = $this->createNewEntityInstance();
        }

        $this->registry[$key]->setValue($value);
    }

    /**
     * {@inheritdocs}
     */
    public function delete($key)
    {
        if ($this->has($key)) {
            unset($this->registry[$key]);
        } else {
            throw new EntityMissingException($key);
        }
    }

    /**
     * {@inheritdocs}
     */
    public function getEntity($key)
    {
        if ($this->has($key)) {
            return $this->registry[$key];
        } else {
            throw new EntityMissingException($key);
        }
    }

    /**
     * Easy way to hook into the entity's instance creation
     * if you wana use your custom entity class,
     * simply extend your custom Manager and overide this function.
     *
     * @return \hisorange\Registry\Interfaces\Entity
     */
    protected function createNewEntityInstance()
    {
        return new Entity;
    }

    /**
     * Implementation to support the \ArrayAccess interface's isset($registry['foo']) syntax.
     *
     * @param string $key
     * @return bool
     */
    public function offsetExists($key)
    {
        $this->has($key);
    }

    /**
     * Implementation to support the \ArrayAccess interface's $registry['foo'] = $value; syntax.
     *
     * @param  string $key
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * Implementation to support the \ArrayAccess interface's $bar = $registry['foo']; syntax.
     * @throws \hisorange\Registry\Exceptions\EntityMissing
     *
     * @param  string $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * Implementation to support the \ArrayAccess interface's unset($registry['foo']); syntax.
     * @throws \hisorange\Registry\Exceptions\EntityMissing
     *
     * @param  string $key
     * @return void
     */
    public function offsetUnset($key)
    {
        $this->delete($key);
    }

    /**
     * Magic call to support the $registry->foo()->revisions() syntax.
     * @throws \hisorange\Registry\Exceptions\EntityMissing
     *
     * @param  string $method
     * @return mixed
     */
    public function __call($method, $args = array())
    {
        return $this->getEntity($method);
    }

    /**
     * Magic accessor to support the $registry->foo->revisions() syntax.
     * @throws \hisorange\Registry\Exceptions\EntityMissing
     *
     * @param  string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getEntity($key);
    }
}