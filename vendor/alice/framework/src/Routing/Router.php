<?php namespace Alice\Routing;

use Alice\Core\Application;
use Alice\Core\AliceException;
use Alice\Config\Config;

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
        $uri = substr(urldecode($_SERVER['REQUEST_URI']), strlen($basepath));

        // Remove every possible custom query string... like '?param=test'
        if (strstr($uri, '?'))
        {
            $uri = substr($uri, 0, strpos($uri, '?'));
        }

        // If the user browse to public/ without providing anything then $uri will be empty
        if (!empty($uri))
        {
            // Remove index.php if used in the url.
            $uri = preg_split('/^index.php\//', rtrim($uri, '/'), -1, PREG_SPLIT_NO_EMPTY)[0];

            // Remove trailing '/'.
            $uri = trim($uri, '/');
        }

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
    {
        $found = false;
        $index = 0;
        $arrayOfMatches = array();
        $splittedURI = explode('/', $currentURI);

        foreach (self::$bindedRoutes as $route)
        {
            // No need to analyze POST requests here.
            if ($route->getType() === 'GET')
            {
                // Easiest case first.
                if ($route->getURI() === $currentURI && !$route->hasParams())
                {
                    // Gotcha.
                    $found = true;
                    $this->routeIndex = $index;

                    // No need to continue wasting time.
                    break;
                }

                $numberOfMatches = 0;
                $routeURISplit = explode('/', $route->getURI());

                if (($splittedURI[0] === $routeURISplit[0]) && $route->hasParams())
                {
                    $numberOfMatches++;

                    /*
                     * I need this to eliminate routes like
                     * users/list when searching for users/1
                     */
                    if (count($routeURISplit) > count($splittedURI))
                    {
                        $numberOfMatches = 0;
                    }

                    for ($i = 1; $i < count($splittedURI); $i++)
                    {
                        if (isset($routeURISplit[$i]))
                        {
                            if ($routeURISplit[$i] === $splittedURI[$i])
                                $numberOfMatches++;
                            else
                                $numberOfMatches--;
                        }
                    }

                    $arrayOfMatches[$index] = $numberOfMatches;
                }
            }
            // Incrementing index for next array entry.
            $index++;
        }

        // Now try to find the Route with the same amount of params if I still don't have a match.
        if (!$found && !empty($arrayOfMatches))
        {
            $highestMatches = array_keys($arrayOfMatches, max($arrayOfMatches));
            foreach ($highestMatches as $match)
            {
                $matchedRoute = self::$bindedRoutes[$match];
                $paramsPortion = ltrim(str_replace($matchedRoute->getURI(), '', $currentURI), '/');

                // If $paramsPortion is empty it means that no params are passed.
                $paramsCount = (empty($paramsPortion)) ? 0 : count(explode('/', $paramsPortion));

                if ($paramsCount == $matchedRoute->paramsCount())
                {
                    $found = true;

                    $this->routeIndex = $match;
                    $this->routeParams = explode('/', $paramsPortion);

                    // No need to continue on.
                    break;
                }
            }
        }

        return $found;
    }

    /**
     * This method is used to send a 404 response code to the browser, and also show
     * a 404 page.
     */
    private function show404()
    {
        if (function_exists('http_response_code'))
            http_response_code(404);
        else
            header('HTTP/1.1 404 Not Found');

        echo "<h1>Not Found</h1>";
        echo "The requested URL was not found on this server.";

        die();
    }

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
         * If URI corresponds to index.php or is empty just redirect to
         * the GET route without parameters called 'index'.
         */
        if ($currentURI === 'index.php'|| $currentURI === '') $currentURI = 'index';

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
            // If params are needed set them now.
            if (isset($this->routeParams))
                self::$bindedRoutes[$this->routeIndex]->setParams($this->routeParams);

            // Dispatch the Route.
            self::$bindedRoutes[$this->routeIndex]->dispatch();
        }
        else
        {
            // Handle route not found depending on router.handle_with_controller configuration.

            $handleWithController = false;

            if (Config::get('router.handle_with_controller'))
            {
                // First of all check that handle_controller config is using a valid format (Controller@Method).
                $controller = Config::get('router.handle_controller');
                if (preg_match('/^\w+@\w+$/', $controller))
                {
                    list($controller, $method) = explode('@', $controller);

                    $controllerPath = Application::getPath('path.controllers') . DIRECTORY_SEPARATOR . $controller . '.php';
                    if (file_exists($controllerPath))
                    {
                        $handleWithController = true;
                    }
                }
            }

            if ($handleWithController)
            {
                require_once $controllerPath;
                $handler = new $controller;

                if (method_exists($handler, $method))
                {
                    // Do I need to pass route?
                    if (Config::get('router.pass_route'))
                        $handler->$method($currentURI);
                    else
                        $handler->$method();
                }
                else
                {
                    // Specified method to handle 404 doesn't exists.
                    throw new AliceException($GLOBALS['404_METHOD_NOT_FOUND_MESSAGE'], $GLOBALS['404_METHOD_NOT_FOUND_CODE']);
                }
            }
            else
            {
                // Route not found, show 404 page.
                $this->show404();
            }
        }
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
     *
     * @param string $location The relative location of the redirect.
     * @param int $status_code The status code, by default is 301.
     */
    public static function redirect($location, $status_code = 301)
    {
        /*
         * Even though I prefer using HTTP 303 status code (See Other), I stick with
         * HTTP 301 (Moved Permanently) because it looks like that many pre-HTTP/1.1 user agents
         * are incompatible.
         * See http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html#sec10.3.4 for details.
         */

        $location = Config::get('application.base_url') . '/' . $location;

        header('Location: ' . $location, true, $status_code);

        /*
         * The use of die() is mandatory here.
         * More info: http://thedailywtf.com/Articles/WellIntentioned-Destruction.aspx
         */
        die();
    }

    /**
     * This method is used to get the Route URI based on its name.
     *
     * @param string $route_name The name of the Route.
     * @throws AliceException    If named Route doesn't exists.
     */
    public static function route($route_name)
    {
        foreach (self::$bindedRoutes as $route)
        {
            if ($route->getName() === $route_name)
            {
                return $route->getURI();
                break;
            }
        }

        // Route doesn't exists, throw an exception.
        throw new AliceException($GLOBALS['NAMED_ROUTE_NOT_FOUND_MESSAGE'], $GLOBALS['NAMED_ROUTE_NOT_FOUND_CODE']);
    }
}
