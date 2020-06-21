<?php

namespace App;

class Container
{
    protected static array $services = [];

    /**
     * Add a service to the container.
     *
     * @param string $key
     * @param $service
     */
    public static function add(string $key, $service): void
    {
        self::$services[$key] = $service;
    }

    /**
     * Get classes from the container.
     *
     * @param string $key
     * @return mixed|null
     */
    public static function get(string $key)
    {
        return self::$services[$key] ?? null;
    }
}
