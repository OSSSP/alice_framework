<?php namespace Alice\Routing;

use Alice\Core\Application;
use Alice\Core\AliceException;

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
     * This method is used to get the current URI.
     *
     * @return string The urldecoded URI without basepath.
     */
    private function getURI()
    {
        // SCRIPT_NAME will always be path/to/index.php

        // Get URI starting after the basepath.
        $basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
        $uri = substr($_SERVER['REQUEST_URI'], strlen($basepath));

        // Remove every possible custom query string... like '?param=test'
        if (strstr($uri, '?'))
        {
            $uri = substr($uri, 0, strpos($uri, '?'));
        }

        // Remove index.php if used in the url.
        $uri = preg_split('/^index.php\//', rtrim($uri, '/'), -1, PREG_SPLIT_NO_EMPTY)[0];

        // Remove trailing '/'.
        $uri = trim($uri, '/');

        return urldecode($uri);
    }

    /**
     * This method is used to start the routing process.
     * It will get the current URI, try to match a Route and
     * finally dispatch it.
     */
    public function startRouting()
    {
        $currentURI = $this->getURI();

        die($currentURI);
    }

    /**
     * This method is used to check whether a route already exists or not.
     *
     * @param string $route The wannabe Route.
     * @return boolean      True if exists, false otherwise.
     */
    private static function routeExists($route)
    {
        foreach (self::$bindedRoutes as $bindedRoute)
        {
            if ($bindedRoute->getURI() === $route->getURI() && ($bindedRoute->getType() === $route->getType()))
            {
                // If it is a GET Route check if parameters count is the same.
                // Else if it is a POST Route check if parameters are the same.

                if (($route->getType() === 'GET') && $bindedRoute->paramsCount() == $route->paramsCount())
                {
                    return true;
                }
                else if (($route->getType() === 'POST') && ($bindedRoute->getParams() == $route->getParams()))
                {
                    return true;
                }
            }
        }

        // Route is unique.
        return false;
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
            throw new AliceException($GLOBALS['ROUTE_ALREADY_EXISTS_MESSAGE'], $GLOBALS['ROUTE_ALREADY_EXISTS_CODE']);
        }
        else
        {
            // Add this object to the list of routes.
            array_push(self::$bindedRoutes, $routeObject);
        }
    }

     /**
     * This method is used to register a POST Route.
     *
     * @param string $route      The actual Route to register.
     * @param string $handler    The controller-method pair separated by a '@'.
     * @param string $route_name The name of the Route [optional].
     * @throws AliceException    If the Route already exists.
     */
    public static function post($route, $handler, $route_name = false)
    {
        $routeObject = new Route();
        $routeObject->registerPOST($route, $handler, $route_name);

        if (self::routeExists($routeObject))
        {
            throw new AliceException($GLOBALS['ROUTE_ALREADY_EXISTS_MESSAGE'], $GLOBALS['ROUTE_ALREADY_EXISTS_CODE']);
        }
        else
        {
            // Add this object to the list of routes.
            array_push(self::$bindedRoutes, $routeObject);
        }
    }

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
