<?php namespace Alice\Core;

use Alice\Config\Config;
use Alice\Core\Application;

class AliceException extends \Exception
{
    public function __construct($message, $code, $previous = null)
    {
        // Make sure everything is assigned properly.
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}";
    }

    public static function printException(AliceException $e)
    {
        echo 'Uncaught ' . get_class($e) . ', code: ' . $e->getCode() . "<br />Message: " . htmlentities($e->getMessage()) . "<br />";
    }

    public static function handleException(AliceException $e)
    {
        // Check if Exception must be logged.
        // TODO: implement logging.

        $handleWithController = false;

        if (Config::get('exception.handle_with_controller'))
        {
            // First of all check that handle_controller config is using a valid format (Controller@Method).
            $controller = Config::get('exception.handle_controller');
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
            try
            {
                require_once $controllerPath;
                $handler = new $controller;

                if (method_exists($handler, $method))
                {
                    $handler->$method($e);
                }
                else
                {
                    // Specified method to handle exception doesn't exists.
                    throw new AliceException($GLOBALS['EXCEPTION_METHOD_NOT_FOUND_MESSAGE'], $GLOBALS['EXCEPTION_METHOD_NOT_FOUND_CODE']);
                }
            }
            catch (AliceException $sub_e)
            {
                // Something went wrong, just notify like default.
                self::printException($sub_e);
            }
        }
        else
        {
            self::printException($e);
        }
    }
}
