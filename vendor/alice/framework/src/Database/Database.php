<?php namespace Alice\Database;

use Alice\Core\Application;
use Alice\Core\AliceException;
use Alice\Config\Config;

class Database
{
    /**
     * @var string
     */
    private $dbHost = '';

    /**
     * @var string
     */
    private $dbUser = '';

    /**
     * @var string
     */
    private $dbPass = '';

    /**
     * @var string
     */
    private $dbName = '';

    /**
     * @var string
     */
    private $dbConnectionString;

    /**
     * @var object
     */
    private $dbConnection;

    /**
     * @var array
     */
    private $options;


    /**
     * @var bool
     */
    private $handleWithController = false;
    /**
     * @var string
     */
    private $controllerPath;
    /**
     * @var string
     */
    private $controller;
    /**
     * @var string
     */
    private $method;


    /**
     * @var object|bool
     */
    private $statement;

    /**
     * @var string
     */
    private $query;

    /**
     * This method is used to construct the Object, it will setup basic options
     * and establish a connection to the database.
     *
     * @throws AliceException If the connection fails or if Controller or Method
     *                        doesn't exists.
     */
    public function __construct()
    {
        // Get credentials from config.
        $this->dbHost = Config::get('database.host');
        $this->dbUser = Config::get('database.username');
        $this->dbPass = Config::get('database.password');
        $this->dbName = Config::get('database.database');

        // First of all check if I need to use a controller in case of error.
        if (Config::get('database.handle_with_controller'))
        {
            // Check that handle_controller config is using a valid format (Controller@Method).
            $controller = Config::get('database.handle_controller');
            if (preg_match('/^\w+@\w+$/', $controller))
            {
                list($this->controller, $this->method) = explode('@', $controller);

                $this->controllerPath = Application::getPath('path.controllers') . DIRECTORY_SEPARATOR . $this->controller . '.php';
                if (file_exists($this->controllerPath))
                {
                    $this->handleWithController = true;
                }
                else
                {
                    throw new AliceException($GLOBALS['DB_CONTROLLER_NOT_FOUND_MESSAGE'], $GLOBALS['DB_CONTROLLER_NOT_FOUND_CODE']);
                }
            }
        }

        // For now I plan to use only MySQL, probably I will introduce different drivers in the future.
        $this->dbConnectionString = "mysql:host={$this->dbHost};dbname={$this->dbName}";

        // It's time to specify options.
        $this->options = array(
            \PDO::ATTR_PERSISTENT => true,  // Increasing performance
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION  // This will throw an exception if an error occurs
        );

        // Also include user defined options.
        $userOptions = Config::get('database.options');
        if ($userOptions && is_array($userOptions))
        {
            foreach ($userOptions as $option => $value)
            {
                $this->options[$option] = $value;
            }
        }

        // Attempt to establish a connection to the database.
        try
        {
            $this->dbConnection = new \PDO($this->dbConnectionString, $this->dbUser, $this->dbPass, $this->options);
        }
        catch (\PDOException $e)
        {
            // Unable to make a connection, notify to user depending on config.
            if ($this->handleWithController)
            {
                require_once $this->controllerPath;
                $handler = new $this->controller;

                if (method_exists($handler, $this->method))
                {
                    $handler->{$this->method}($e->getMessage());

                    // This is required to prevent further execution.
                    die();
                }
                else
                {
                    // Specified method to handle DB error doesn't exists.
                    throw new AliceException($GLOBALS['DB_METHOD_NOT_FOUND_MESSAGE'], $GLOBALS['DB_METHOD_NOT_FOUND_CODE']);
                }
            }
            else
            {
                // Just throw an exception to handle within custom exception handler.
                throw new AliceException($GLOBALS['DB_CONNECTION_ERROR_MESSAGE'], $GLOBALS['DB_CONNECTION_ERROR_CODE']);
            }
        }
    }

    /**
     * This method is used to prepare a statement for execution.
     *
     * @param string $query The actual SQL statement.
     */
    public function query($query)
    {
        $this->query = $query;
        $this->statement = $this->dbConnection->prepare($query);
    }

    /**
     * This method is used to guess the parameter type.
     * Note: only basic types are implemented.
     *
     * @return The corresponding PDO::PARAM_* constant.
     */
    private function guessType($value)
    {
        if (is_int($value))
            return \PDO::PARAM_INT;
        elseif (is_bool($value))
            return \PDO::PARAM_BOOL;
        elseif (is_null($value))
            return \PDO::PARAM_NULL;
        else
            return \PDO::PARAM_STR;
    }

    /**
     * This method is used to bind a value or an array of values to
     * its corresponding parameter/s.
     * This function is declared as a variadic function hence it will
     * accept a variable amount of parameters.
     * Allowed parameters are:
     *
     * @param array $parameters     The array of parameters (parameter => value).
     * ---- OR ----
     * @param string $parameter     The parameter placeholder.
     * @param string $value         The value to bind to the parameter.
     * ---- AND ----
     * @param PDO::PARAM_*|int type The explicit data type for the parameter.
     *
     * @throws AliceException       If an error occur.
     */
    public function bind()
    {
        switch (func_num_args())
        {
            case 1:
                // Array of parameters
                $params = func_get_arg(0);
                if (is_array($params))
                {
                    foreach ($params as $parameter => $value)
                    {
                        $this->statement->bindValue($parameter, $value, $this->guessType($value));
                    }
                }
                else
                {
                    // Array was expected.
                    throw new AliceException($GLOBALS['DB_PARAM_BIND_ERROR_MESSAGE'], $GLOBALS['DB_PARAM_BIND_ERROR_CODE']);
                }

                break;
            case 2:
                // 0: parameter 1: value
                $parameter = func_get_arg(0);
                $value = func_get_arg(1);

                $this->statement->bindValue($parameter, $value, $this->guessType($value));

                break;
            case 3:
                // 0: parameter 1: value 2: type
                $parameter = func_get_arg(0);
                $value = func_get_arg(1);
                $type = func_get_arg(2);

                // Throw exception if parameter type is invalid.
                if (is_int($type))
                    $this->statement->bindValue($parameter, $value, $type);
                else
                    throw new AliceException($GLOBALS['DB_PARAM_BIND_ERROR_MESSAGE'], $GLOBALS['DB_PARAM_BIND_ERROR_CODE']);

                break;
            default:
                // Only 3 overloads are available.
                throw new AliceException($GLOBALS['DB_PARAM_BIND_ERROR_MESSAGE'], $GLOBALS['DB_PARAM_BIND_ERROR_CODE']);
        }
    }

    /**
     * This method is used to execute a prepared statement.
     *
     * @throws AliceException If there is an error with the DB or if the method specified for error
     *                        reporting doesn't exists.
     */
    public function execute()
    {
        try
        {
            return $this->statement->execute();
        }
        catch(\PDOException $e)
        {
            // An error occurred, notify to user depending on config
            if ($this->handleWithController)
            {
                require_once $this->controllerPath;
                $handler = new $this->controller;

                if (method_exists($handler, $this->method))
                {
                    // Do I need to pass the query?
                    if (Config::get('database.pass_query'))
                        $handler->{$this->method}($e->getMessage(), $this->query);
                    else
                        $handler->{$this->method}($e->getMessage());

                    // This is required to prevent further execution.
                    die();
                }
                else
                {
                    // Specified method to handle DB error doesn't exists.
                    throw new AliceException($GLOBALS['DB_METHOD_NOT_FOUND_MESSAGE'], $GLOBALS['DB_METHOD_NOT_FOUND_CODE']);
                }
            }
            else
            {
                // Just throw an exception to handle within custom exception handler.
                throw new AliceException($GLOBALS['DB_SYNTAX_ERROR_MESSAGE'], $GLOBALS['DB_SYNTAX_ERROR_CODE']);
            }
        }
    }

    /**
     * This method is used to execute a prepared statement and fetch
     * all the tuples in a result set.
     *
     * @return array|bool The result set from the query or False on failure.
     */
    public function getResultSet()
    {
        $this->execute();

        /*
         * PDOStatement::fetchAll() returns an array containing all of the remaining rows in the result set.
         * An empty array is returned if there are zero results to fetch, or FALSE on failure.
         */
        return $this->statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * This method is used to execute a prepared statement and fetch a single record from the
     * result set associated with the statement.
     *
     * @return array|bool The record or False on failure.
     */
    public function getNextRow()
    {
        $this->execute();

        // PDOStatement::fetch() In all cases, FALSE is returned on failure.
        return $this->statement->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * This method is used to get the number of rows affected by the last statement.
     *
     * @return int The number of affected rows.
     */
    public function rowCount()
    {
        return $this->statement->rowCount();
    }

    /**
     * This method is used to get the ID of the last insert statement.
     *
     * @return string The row ID of the last inserted row.
     */
    public function lastInsertId()
    {
        return $this->dbConnection->lastInsertId();
    }

    /**
     * This method is used to begin a transaction.
     *
     * @return bool True on success, False on failure.
     */
    public function beginTransaction()
    {
        return $this->dbConnection->beginTransaction();
    }

    /**
     * This method is used to commit a transaction.
     *
     * @return bool True on success, False on failure.
     */
    public function commitTransaction()
    {
        return $this->dbConnection->commit();
    }

    /**
     * This method is used to roll back a transaction.
     *
     * @return bool True on success, False on failure.
     */
    public function rollbackTransaction()
    {
        return $this->dbConnection->rollback();
    }
}
