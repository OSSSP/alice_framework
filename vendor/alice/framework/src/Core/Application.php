<?php namespace Alice\Core;

class Application
{
    protected static $paths = array();

    public function __construct()
    {
        //echo "I'm Application.<br />";
    }

    public function run()
    {
        // Get ?url and remove trailing '/'
        // If no controller is specified then load index by default
        $url = rtrim(isset($_GET['url']) ? $_GET['url'] : 'index', '/');

        $url = explode('/', $url);

        //var_dump($url);
        //echo "<br />";

        // The first portion of the url will always be the the name of the controller
        $controller_path = Application::getPath('path.controllers') . DIRECTORY_SEPARATOR . "{$url[0]}Controller.php";

        // Before requiring the controller lets check if file exists
        if (file_exists($controller_path))
        {
            require $controller_path;
        }
        else
        {
            // TODO: it would be nice to implement an exception handler.
            throw new \Exception("Controller {$controller_path} not found.", 1);
        }

        // Instantiate the Controller
        $controller_name = $url[0] . 'Controller';
        $controller = new $controller_name;

        // The second portion of the url will always be the method, but I should check
        // if a parameter is passed to the method before calling it.
        if (isset($url[2]))
        {
            // controller/method/parameter
            if (method_exists($controller, $url[1]))
            {
                $controller->{$url[1]}($url[2]);
            }
            else
            {
                throw new \Exception("Controller {$controller_path} doesn't have a {$url[1]} method.", 1);
            }
        }
        else if (isset($url[1]))
        {
            // controller/method
            if (method_exists($controller, $url[1]))
            {
                $controller->{$url[1]}();
            }
            else
            {
                throw new \Exception("Controller {$controller_path} doesn't have a {$url[1]} method.", 1);
            }
        }
        else
        {
            // If no method specified then call index() method by default
            $controller->index();
        }
    }

    public function bindPaths(array $paths)
    {
        foreach ($paths as $key => $value)
        {
            if (!isset(self::$paths["path.{$key}"]))
                self::$paths["path.{$key}"] = realpath($value);
        }
    }

    public static function getPath($path)
    {
        return isset(self::$paths[$path]) ? self::$paths[$path] : false;
    }
}
