<?php

use Alice\Core\Application;

class ApplicationTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->app = new Application();
    }

    public function checkPaths()
    {
        return [
            ['path.controllers', realpath('../../../application/controllers')],
            ['path.models', realpath('../../../application/models')],
            ['path.views', realpath('../../../application/views')]
        ];
    }

    public function returnPaths()
    {
        return array(
            'controllers' => '../../../application/controllers',
            'models' => '../../../application/models',
            'views' => '../../../application/views'
        );
    }

    /**
    * Pretty useless test, used to check if phpunit is properly working.
     */
    public function testApplicationIsOfTheRightType()
    {
        $this->assertInstanceOf('Alice\Core\Application', $this->app);
    }

    public function testInvalidPathsAreNotBinded()
    {
        $this->app->bindPaths(array('application' => 'this/is/an/invalid/path'));
        $this->assertFalse($this->app->getPath('application'));
    }

    /**
     * @dataProvider checkPaths
     */
    public function testPathsAreBinded($key, $value)
    {
        $this->assertClassHasStaticAttribute('paths', 'Alice\Core\Application');

        $this->app->bindPaths($this->returnPaths());

        $this->assertEquals($value, $this->app->getPath($key));
    }
}
