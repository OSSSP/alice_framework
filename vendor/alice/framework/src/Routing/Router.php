<?php namespace Alice\Routing;

class Router
{
    /**
     * @var array
     */
    private static $bindedRoutes = array();

    /**
     * @var string
     */
    private $requestMethod;

    /**
     * @var int
     */
    private $routeIndex;

    /**
     * @var array
     */
    private $routeParams = null;

    public function __construct()
    {
        echo "I'm Router::__construct()<br />";
    }

    /**
     * This method is used to start the routing process.
     */
    public function startRouting()
    {}

    /**
     * This method is used to register a GET route.
     */
    public static function get()
    {}

    /**
     * This method is used to register a POST route.
     */
    public static function post()
    {}

    /**
     * This method is used to redirect to a custom location.
     */
    public static function redirect()
    {}

    /**
     * This method is used to get the Route URI based on its name.
     */
    public static function route()
    {}
}
