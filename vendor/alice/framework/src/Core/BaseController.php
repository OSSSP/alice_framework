<?php namespace Alice\Core;

/**
 * This is the BaseController class
 *
 * The other controllers will inherit this one
 * so that they can access Views
 *
 */
class BaseController
{
    public function __construct()
    {
        //echo "I'm BaseController.<br />";

        // Instantiate the View object
        $this->view = new BaseView();
    }
}
