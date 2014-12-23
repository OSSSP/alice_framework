<?php

class testController extends Alice\Core\BaseController
{
    public function __construct()
    {
        // I need to call parent constructor
        parent::__construct();

        //echo "I'm testController<br />";
    }

    public function test($parameter = false)
    {
        //echo "I'm test() method";

        if ($parameter)
            echo ", and I have this parameter: {$parameter}<br />";
        else
            echo "<br />";

        // Require the model to handle user input.
        require __DIR__ . '/../models/testModel.php';
        $model = new testModel();
    }
}
