<?php namespace Alice\Routing;

use Alice\Core\Application;

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

        // If application/routes.php exists then load routes from there.
        $routesPath = Application::getPath('path.application') . DIRECTORY_SEPARATOR . 'routes.php';
        if (file_exists($routesPath))
        {
            require_once $routesPath;
        }
    }

    /**
     * This method is used to start the routing process.
     */
    public function startRouting()
    {}

    private static function routeExists($route)
    {
    }

    /**
     * This method is used to register a GET Route.
     *
     * @param string $route      The actual Route to register.
     * @param string $handler    The controller-method pair separated by a '@'.
     * @param string $route_name The name of the Route [optional].
     * @throws AliceException    If the Route already exists.
     */
    public static function get($route, $handler, $route_name = false)
    {
        $routeObject = new Route();
        $routeObject->registerGET($route, $handler, $route_name);

        if (self::routeExists($routeObject))
        {
            // TODO: throw exception
        }
        else
        {
            // TODO: add this object to the list of routes.
        }
    }

    /**
     * This method is used to register a POST Route.
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
