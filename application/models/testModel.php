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

    public function testDB()
    {
        $this->db->query('INSERT INTO users (username, password) VALUES (:username, :password)');

        $this->db->bind(':username', 'user12', 2);
        $this->db->bind(':password', hash('sha256', 'password'));

        $result = $this->db->execute();

        var_dump($result);
    }
}
