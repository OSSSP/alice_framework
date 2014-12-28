<?php

class testModel extends Alice\Core\BaseModel
{
    public function __construct()
    {
        // Call parent constructor
        parent::__construct();

        //echo "I'm testModel.<br />";
    }

    public function testMethod()
    {
        return 'return value of testMethod()';
    }
}
