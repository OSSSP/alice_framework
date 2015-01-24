<?php

class testController extends Alice\Core\BaseController
{
    public function __construct()
    {
        // I need to call parent constructor
        parent::__construct();

        //echo "I'm testController<br />";
    }

    public function index()
    {
        $this->view->renderView('test/index');
    }

    public function test($parameter = false)
    {
        //echo "I'm test() method";

        $result = $this->model->testMethod();
        $this->view->result = $result;

        $this->view->renderView('test/result');
    }

    public function showUsers()
    {
        echo "I'm testController@showUsers()<br />";
    }

    public function showUserA($id)
    {
        echo "I'm testController@showUserA() with: $id<br />";
    }

    public function showUserB($id)
    {
        echo "I'm testController@showUserB() with: $id<br />";
    }

    public function showUserC($param1, $param2)
    {
        echo "I'm testController@showUserC() with: <br />";
        echo "Param1: $param1<br />Param2: $param2<br />";
    }

    public function showUserD($param1, $param2, $param3)
    {
        echo "I'm testController@showUserD() with: <br />";
        echo "Param1: $param1<br />Param2: $param2<br />Param3: $param3<br />";
    }

    public function showPost($id, $hello)
    {
        echo "I'm testController@showPost() with:<br />";
        echo "ID:<br />";
        var_dump($id);
        echo "<br />Hello:<br />";
        var_dump($hello);
    }

    public function showPostA($id, $username)
    {
        echo "I'm testController@showPostA() with:<br />";
        echo "ID:<br />";
        var_dump($id);
        echo "<br />Username:<br />";
        var_dump($username);
    }
}
