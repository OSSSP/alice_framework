<?php

class Application
{
    public function __construct()
    {
        // Get ?url and remove trailing '/'
        $url = rtrim(isset($_GET['url']) ? $_GET['url'] : null, '/');

        $url = explode('/', $url);

        var_dump($url);
        echo "<br />";

        // The first portion of the url will always be the the name of the controller
        // TODO: it would be better to implemento a getPath() method.
        $controller_path = __DIR__ . '/../../../../../application/controllers/' . $url[0] . 'Controller.php';

        // Before requiring the controller lets check if file exists
        if (file_exists($controller_path))
        {
            require $controller_path;
        }
        else
        {
            // TODO: it would be nice to implement an exception handler.
            throw new Exception("Controller {$controller_path} not found.", 1);
        }

        // Instantiate the Controller
        $controller_name = $url[0] . 'Controller';
        $controller = new $controller_name;

        // The second portion of the url will always be the method, but we should check
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
}
