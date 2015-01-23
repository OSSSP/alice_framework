<?php namespace Alice\Routing;

use Alice\Core\Application;

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
     * This method is used to check whether specified Controller exists.
     *
     * @return boolean True if Controller exists.
     */
    private function controllerExists()
    {
        $controllerPath = Application::getPath('path.controllers') . DIRECTORY_SEPARATOR . $this->routeController . '.php';

        return file_exists($controllerPath);
    }

    /**
     * This method is used to set the Controller and the corresponding Method.
     *
     * @param string $handler The Controller-Method pair separated by a '@'.
     * @return boolean        True if valid, false otherwise.
     */
    private function setController($handler)
    {
        $controllerVars = explode('@', $handler);
        if (isset($controllerVars[0], $controllerVars[1]) && !empty($controllerVars[0]) && !empty($controllerVars[1]))
        {
            $this->routeController = $controllerVars[0];
            $this->routeMethod = $controllerVars[1];

            // This can be replaced with return $this->controllerExists();

            if ($this->controllerExists())
                return true;
            else
                return false;
        }
        else
            return false;
    }

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
    {
        // First thing to do is to remove trailing '/' from the beginning and the end
        // of the route.
        $route = trim($route, '/');

        // Now let's try to recognize what type of route is this.
        if (preg_match('/^(([a-z]+)(?:\/|\n|\b))+$/', $route))
        {
            // GET request without parameters.

            echo "GET request without params<br />";

            $this->routeName = ($route_name != false) ? $route_name : null;
            $this->routeURI = $route;
        }
        elseif (preg_match('/^(([a-z]+)(?:\/|\n))+(\{[a-z0-9]+\}\/?)+$/', $route))
        {
            // GET request with parameters.

            echo "GET request with params<br />";

            $this->routeName = ($route_name != false) ? $route_name : null;
            $this->routeURI = preg_split('/\/(\{[a-z0-9]+\}\/?)+$/', $route, -1, PREG_SPLIT_NO_EMPTY)[0];

            $routePieces = explode('/', $route);
            foreach ($routePieces as $piece)
            {
                if (preg_match('/^{([a-z0-9]+)}$/', $piece, $parameter))
                {
                    // Add the new parameter to route's parameters.
                    $this->routeParams[$parameter[1]] = null;
                }
            }
        }
        else
        {
            // This is an invalid route.
            // TODO: throw Exception.
        }

        // Now, for the controller part, it will always be Controller@Method
        if (!$this->setController($handler))
        {
            // Invalid Controller/Method.
            // TODO: throw exception
        }
    }

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
