<?php
namespace hisorange\Registry\Interfaces;

interface Manager
{
    /**
     * Register the current instance as the shared global instance.
     *
     * @return void
     */
    public function registerAsGlobal();

    /**
     * Access the stored value in entity form.
     * @throws \hisorange\Registry\Exceptions\EntityMissing
     *
     * @param  string $key
     * @return \hisorange\Registry\Interfaces\Entity
     */
    public function getEntity($key);

    /**
     * Check if the key is registered.
     *
     * @param  string $key
     * @return bool
     */
    public function has($key);

    /**
     * Get a value from the registry or use the fallback if not registered.
     *
     * @param  string $key
     * @param  mixed  $fallback Null when not given.
     * @return mixed
     */
    public function get($key, $fallback = null);

    /**
     * Set a value in the registry.
     *
     * @param  string $key
     * @param  mixed  $value
     * @return void
     */
    public function set($key, $value);

    /**
     * Delete an entity if registered, throws exception if not registered.
     * @throws \hisorange\Registry\Exceptions\EntityMissing
     *
     * @param  string $key
     * @return void
     */
    public function delete($key);
}