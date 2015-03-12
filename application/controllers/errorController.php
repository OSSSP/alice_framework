<?php

use Alice\Core\BaseController;
use Alice\Core\AliceException;

class errorController extends BaseController
{
    public function __construct()
    {
        // first of all call parent constructor
        parent::__construct();
    }

    public function error404()
    {
        echo "errorController: Got a 404 page.";
    }
}
