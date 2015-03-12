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

    public function handleDBError($error, $query = false)
    {
        echo "errorController@handleDBError<br />";
        var_dump($error);
        echo "<br />";
        if ($query)
            var_dump($query);
    }
}
