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
}
