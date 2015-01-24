<?php namespace Alice\Routing;

use Alice\Core\Application;
use Alice\Core\AliceException;

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
     * This method is used to get the Type of the Route.
     *
     * @return string The type of the Route (GET or POST).
     */
    public function getType()
    {
        return $this->routeType;
    }

    /**
     * This method is used to get the Name of the Route.
     *
     * @return NULL|string The name of the route if specified, NULL otherwise.
     */
    public function getName()
    {
        return $this->routeName;
    }

    /**
     * This method is used to get the URI of the Route.
     *
     * @return string The URI of the Route (path/to/route).
     */
    public function getURI()
    {
        return $this->routeURI;
    }

    /**
     * This method is used to check if the Route has parameters.
     *
     * @return boolean True if has parameters, false otherwise.
     */
    public function hasParams()
    {
        return (!empty($this->routeParams)) ? true : false;
    }

    /**
     * This method is used to get the number of parameters of the Route.
     *
     * @return int The parameters count of the Route.
     */
    public function paramsCount()
    {
        return count($this->routeParams);
    }

    /**
     * This method is used to get the parameters of the Route.
     *
     * @return array The array of parameters of the Route.
     */
    public function getParams()
    {
        return $this->routeParams;
    }

    /**
     * This method is used to set the parameters of the Route.
     *
     * @param array $params The array of parameters to set.
     */
    public function setParams($params)
    {
        if ($this->routeType === 'POST')
        {
            $this->routeParams = array_replace($this->routeParams, $params);
        }
        elseif ($this->routeType === 'GET')
        {
            // GET params are stored differently.

            $index = 0;
            foreach ($this->routeParams as $paramName => $paramValue)
            {
                $this->routeParams[$paramName] = $params[$index];
                $index++;
            }
        }
    }

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
     * @throws AliceException      If Route is invalid or if Controller/Method is invalid.
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
            throw new AliceException($GLOBALS['INVALID_GET_ROUTE_MESSAGE'], $GLOBALS['INVALID_GET_ROUTE_CODE']);
        }

        // Now, for the controller part, it will always be Controller@Method
        if (!$this->setController($handler))
        {
            // Invalid Controller/Method.
            throw new AliceException($GLOBALS['INVALID_ROUTE_HANDLER_MESSAGE'], $GLOBALS['INVALID_ROUTE_HANDLER_CODE']);
        }
    }

     /**
     * This method is used to register a POST Route.
     *
     * @param string $route        The actual Route to register.
     * @param string $handler      The Controller-Method pair separated by a '@'.
     * @param string $route_name   The name of the Route [optional].
     * @throws AliceException      If Route is invalid or if Controller/Method is invalid.
     */
    public function registerPOST($route, $handler, $route_name = false)
    {
        // First this to do is to remove trailing '/' from the beginning and the end
        // of the route.
        $route = trim($route, '/');

        // Now let's check if this is a valid POST route.
        if (preg_match('/^(([a-z]+)(?:\/|\n))+(\{[a-z0-9,]+\})$/', $route, $matches))
        {
            // Valid POST request.

            echo "Valid POST request.<br />";

            $this->routeType = 'POST';
            $this->routeName = ($route_name != false) ? $route_name : null;

            // This will spit out the part before the params till the last '/'
            $this->routeURI = rtrim(preg_split('/(\{[a-z0-9,]+\})/', $route)[0], '/');

            // Now for the parameters.
            $routePieces = explode('/', $route);
            foreach ($routePieces as $piece)
            {
                if (preg_match('/^\{([a-z0-9,]+)\}$/', $piece, $parameters))
                {
                    // Those are going to be the parameter of the route.
                    $paramsArray = explode(',', $parameters[1]);
                    foreach ($paramsArray as $param)
                    {
                        $this->routeParams[$param] = null;
                    }
                }
            }
        }
        else
        {
            throw new AliceException($GLOBALS['INVALID_POST_ROUTE_MESSAGE'], $GLOBALS['INVALID_POST_ROUTE_CODE']);
        }

        // Now, for the Controller part, it will always be Controller@Method.
        if (!$this->setController($handler))
        {
            // Invalid Controller/Method.
            throw new AliceException($GLOBALS['INVALID_ROUTE_HANDLER_MESSAGE'], $GLOBALS['INVALID_ROUTE_HANDLER_CODE']);
        }

    }

    /**
     * This method is used to pass the control flow to the Controller.
     */
    public function dispatch()
    {}
}
