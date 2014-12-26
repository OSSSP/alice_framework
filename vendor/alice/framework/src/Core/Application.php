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
        $url = rtrim(isset($_GET['url']) ? $_GET['url'] : null, '/');

        $url = explode('/', $url);

        //var_dump($url);
        //echo "<br />";

        // The first portion of the url will always be the the name of the controller
        //$controller_path = $this->getPath('path.controllers') . "/{$url[0]}Controller.php";
        $controller_path = Application::getPath('path.controllers') . "/{$url[0]}Controller.php";

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
            // TODO: check if method exists.
            $controller->{$url[1]}($url[2]);
        }
        else if (isset($url[1]))
        {
            // controller/method
            $controller->{$url[1]}();
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

    public function getPath($path)
    {
        return isset(self::$paths[$path]) ? self::$paths[$path] : false;
    }
}
