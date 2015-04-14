<?php namespace Krumer\Test\Tools\Dummy;

use Laracasts\TestDummy\Factory;

class Dummy {

    private static $instance;

    /**
     * Fill and save an entity.
     *
     * @param $identifier
     * @param array $overrides
     * @return mixed
     */
    public function store($identifier, array $overrides = [])
    {
        return Factory::create($identifier, $overrides);
    }

    /**
     * Fill an entity with test data, without saving it.
     *
     * @param $identifier
     * @param array $overrides
     * @return mixed
     */
    public function build($identifier, array $overrides = [])
    {
        return Factory::build($identifier, $overrides);
    }

    /**
     * Build an array of dummy attributes for an entity.
     *
     * @param $identifier
     * @param array $overrides
     * @return mixed
     */
    public function attributesFor($identifier, array $overrides = [])
    {
        return Factory::attributesFor($identifier, $overrides);
    }

    /**
     * Calls the static methods on an instance of the class.
     *
     * @param $name
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic($name, array $arguments)
    {
        if ( ! isset(static::$instance))
        {
            static::$instance = new static;
        }

        return call_user_func_array([static::$instance, $name], $arguments);
    }

}