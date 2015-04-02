<?php

class indexController extends Alice\Core\BaseController
{
    public function __construct()
    {
        // I need to call parent constructor
        parent::__construct();

        //echo "I'm indexController<br />";
    }

    public function index()
    {
        // Render homepage view
        $this->view->renderView('home/extendedindex', array(
            'age' => 123
        ));
    }
}
