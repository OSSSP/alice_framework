<?php namespace Alice\Config;

interface ConfigInterface
{
    public static function load();

    public static function get($setting);

    public static function set($key, $value);

    public static function delete($key);
}
