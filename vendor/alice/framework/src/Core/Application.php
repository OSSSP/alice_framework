<?php namespace Alice\Core;

use Alice\Config\Config;
use Alice\Routing\Router;

class Application
{
    protected static $paths = array();

    public function __construct()
    {
        //echo "I'm Application.<br />";
    }

    public function run()
    {
        // First of all let's load configuration.
        Config::load();

        // Start Exception handling
        set_exception_handler('Alice\Core\AliceException::handleException');

        $router = new Router();
        $router->startRouting();
    }

    public function bindPaths(array $paths)
    {
        foreach ($paths as $key => $value)
        {
            $realPath = realpath($value);

            // Do not include invalid paths
            if (!isset(self::$paths["path.{$key}"]) && $realPath != false)
                self::$paths["path.{$key}"] = $realPath;
        }
    }

    public static function getPath($path)
    {
        return isset(self::$paths[$path]) ? self::$paths[$path] : false;
    }
}
