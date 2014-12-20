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

        // First portion of the url will always be the the name of the controller
        $controller_path = __DIR__ . '/../../../../../application/controllers/' . $url[0] . 'Controller.php';
        require $controller_path;

        // Instantiate the Controller
        $controller_name = $url[0] . 'Controller';
        $controller = new $controller_name;
    }
}
