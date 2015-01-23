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
     * This method is used to register a GET Route.
     *
     * @param string $route        The actual Route to register.
     * @param string $handler      The Controller-Method pair separated by a '@'.
     * @param string $route_name   The name of the Route [optional].
     * @throws RouteException      If Route is invalid.
     * @throws ControllerException If Controller/Method is invalid.
     */
    public function registerGET($route, $handler, $route_name = false)
    {}

    /**
     * This method is used to register a POST Route.
     */
    public function registerPOST()
    {}

    /**
     * This method is used to pass the control flow to the Controller.
     */
    public function dispatch()
    {}
}
