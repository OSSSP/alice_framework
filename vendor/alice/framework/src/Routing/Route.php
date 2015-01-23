<?php namespace Alice\Routing;

class Route
{
    /**
     * @var string
     */
    private $routeType = 'GET';

    /**
     * @var string
     */
    private $routeName = '';

    /**
     * @var string
     */
    private $routeURI = '';

    /**
     * @var array
     */
    private $routeParams = array();

    /**
     * @var string
     */
    private $routeController = '';

    /**
     * @var string
     */
    private $routeMethod = '';

    /**
     * This method is used to register a GET route.
     */
    public function registerGET()
    {}

    /**
     * This method is used to register a POST route.
     */
    public function registerPOST()
    {}

    /**
     * This method is used to pass the control flow to the controller.
     */
    public function dispatch()
    {}
}
