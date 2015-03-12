<?php namespace Alice\Core;

use Alice\Database\Database;

class BaseModel
{
    public function __construct()
    {
        //echo "I'm BaseModel.<br />";
        $this->db = new Database();
    }
}
