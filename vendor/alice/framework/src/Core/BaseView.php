<?php namespace Alice\Core;

class BaseView
{
    public function __construct()
    {
        //echo "I'm BaseView.<br />";
    }

    public function renderView($name)
    {
        // TODO: check if view exists
        require Application::getPath('path.views') . "/$name.php";
        //require __DIR__ . "/../../../../../application/views/{$name}.php";
    }
}
