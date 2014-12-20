<?php

class indexController extends BaseController
{
    public function __construct()
    {
        // I need to call parent constructor
        parent::__construct();

        echo "I'm indexController<br />";
    }
}
