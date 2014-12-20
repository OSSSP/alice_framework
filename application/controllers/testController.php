<?php

class testController
{
    public function __construct()
    {
        echo "I'm testController<br />";
    }

    public function test($parameter = false)
    {
        echo "I'm test() method";

        if ($parameter)
            echo ", and I have this parameter: {$parameter}<br />";
        else
            echo "<br />";
    }
}
