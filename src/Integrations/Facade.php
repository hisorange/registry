<?php
namespace hisorange\Registry\Integrations;

// Internals.
use hisorange\Registry\Pool;

/**
 * Register the facade in the class alias map and you can do static
 * calls on the global instance with the Alias::get('xyz'); syntax.
 *
 * class_alias('Registry', 'hisorange\Registry\Integrations\Facade')
 */
final class Facade
{
    /**
     * Mirror the calls to the global instance.
     *
     * @param  string $method
     * @param  array  $args
     * @return mixed
     */
    public function __callStatic($method, $args = [])
    {
        return call_user_func_array([Pool::get(Pool::GLOBAL_NAMESPACE), $method], $args);
    }
}