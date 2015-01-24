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
     * This method is used to get the current HTTP Request Method.
     *
     * @throws AliceException Where method is not supported.
     */
    private function getRequestMethod()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method)
        {
            case 'POST':
                $this->requestMethod = 'POST';
                break;
            case 'GET':
                $this->requestMethod = 'GET';
                break;
            default:
                // Throw exception for unsupported request method.
                throw new AliceException($GLOBALS['UNSUPPORTED_REQUEST_METHOD_MESSAGE'], $GLOBALS['UNSUPPORTED_REQUEST_METHOD_CODE']);
        }
    }

    /**
     * This method is used to try to match a POST request.
     *
     * @param string $currentURI The Route to match.
     * @return boolean           True if found, false otherwise.
     */
    private function matchPOST($currentURI)
    {
        $found = false;
        $index = 0;

        foreach (self::$bindedRoutes as $route)
        {
            // Parenthesis are just for readability
            if ( ($route->getType() === 'POST') && ($route->getURI() === $currentURI) && (array_keys($_POST) == array_keys($route->getParams())) )
            {
                $found = true;

                $this->routeIndex = $index;
                $this->routeParams = $_POST;

                // No need to continue on.
                break;
            }

            $index++;
        }

        return $found;
    }

    /**
     * This method is used to try to match a GET request.
     *
     * @param string $currentURI The Route to match.
     * @return boolean           True if found, false otherwise.
     */
    private function matchGET($currentURI)
    {}

    /**
     * This method is used to start the routing process.
     * It will get the current URI, try to match a Route and
     * finally dispatch it.
     */
    public function startRouting()
    {
        $currentURI = $this->getURI();

        $this->getRequestMethod();

        /*
         * If URI corresponds to index.php just redirect to
         * the GET route without parameters called 'index'.
         */
        if ($currentURI === 'index.php') $currentURI = 'index';

        echo "Trying to resolve: " . $currentURI . "<br />";
        echo "Method: " . $this->requestMethod . "<br />";

        $routeFound = false;
        $routeParams = null;

        if ($this->requestMethod === 'POST')
        {
            // Match the POST request.

            if ($this->matchPOST($currentURI) !== false)
                $routeFound = true;
        }
        elseif ($this->requestMethod === 'GET')
        {
            // Match the GET request.

            if ($this->matchGET($currentURI) !== false)
                $routeFound = true;
        }

        if ($routeFound)
        {
            echo "I've found the correct route... I'm gonna dispatch it now.<br />";

            // If params are needed set them now.

            // Dispatch the Route.
        }
        else
        {
            /*
             * Based on config.what_to_do_when_not_found
             * if I have to handle this within the Router I would just throw an exception
             * else just do what I do on exception handling if controller based.
             */
            echo "Route not found... redirecting to error page.";
        }

        die();
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
