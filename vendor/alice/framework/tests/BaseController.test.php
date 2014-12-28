<?php

use Alice\Core\BaseController;

/**
 * I'm using testController here because in the test application
 * there is a testModel and so I can check for correct Model loading.
 */
class testController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }
}

class BaseControllerTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $returnPathForInvalidController = '';
        $returnPathForValidController = realpath('../../../application/models');

        $this->applicationMock = \Mockery::mock('alias:Alice\Core\Application');
        $this->applicationMock->shouldReceive('getPath')->andReturn($returnPathForInvalidController, $returnPathForValidController);

        $this->controller = new BaseController();
    }

    /**
     * As for Application test... let's keep up with my ugly convention.
     */
    public function testBaseControllerIsOfTheRightType()
    {
        $this->assertInstanceOf('Alice\Core\BaseController', $this->controller);
    }

    public function testControllerHasAssociatedView()
    {
        $this->assertInstanceOf('Alice\Core\BaseView', $this->controller->view);
    }

    public function testNoModelGetsLoadedForInvalidController()
    {
        $this->assertClassHasAttribute('model', 'Alice\Core\BaseController');
        $this->assertNull($this->controller->model);
    }

    public function testCorrespondingModelGetsLoadedForValidController()
    {
        $testController = new testController();
        $this->assertInstanceOf('testModel', $testController->model);
    }

    public function tearDown()
    {
        \Mockery::close();
    }
}
