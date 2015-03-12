<?php namespace Alice\Config;

class ConfigReader
{
    // Default config settings.
    private static $applicationConfig = array();

    private static $routerConfig = array(
        'router.handle_with_controller' => false,
        'router.handle_controller' => null,
        'router.log_exception' => false
    );

    private static $exceptionConfig = array(
        'exception.handle_with_controller' => false,
        'exception.handle_controller' => null,
        'exception.log_exception' => false
    );

    private static $databaseConfig = array(
        'database.handle_with_controller' => false,
        'database.handle_controller' => null,
        'database.log_exception' => false
    );

    // User defined config settings.
    private static $otherConfig = array();

    /**
     * This function is used to add a config setting in the relevant
     * context if available, otherwise it uses $otherConfig.
     *
     */
    public static function _addSetting($key, $value, $context)
    {
        switch ($context)
        {
            case 'application':
                self::$applicationConfig["application.$key"] = $value;
                break;
            case 'router':
                self::$routerConfig["router.$key"] = $value;
                break;
            case 'exception':
                self::$exceptionConfig["exception.$key"] = $value;
                break;
            case 'database':
                self::$databaseConfig["database.$key"] = $value;
                break;
            default:
                if (!$context)
                    self::$otherConfig[$key] = $value;
                else
                    self::$otherConfig["$context.$key"] = $value;
        }
    }

    /**
     * This function is used to retrieve a config setting.
     *
     * @param string $key The actual key.
     * @param bool $enable_context_search Used to speed up the process by reducing the number of iterations.
     */
    public static function _getSetting($key, $enable_context_search = true)
    {
        if ($enable_context_search)
        {
            // Attempt to find a context, only take the portion till the first '.'
            $context = strstr($key, '.', true);

            switch ($context)
            {
                case 'application':
                    if (array_key_exists($key, self::$applicationConfig))
                        return self::$applicationConfig[$key];
                    break;
                case 'router':
                    if (array_key_exists($key, self::$routerConfig))
                        return self::$routerConfig[$key];
                    break;
                case 'exception':
                    if (array_key_exists($key, self::$exceptionConfig))
                        return self::$exceptionConfig[$key];
                    break;
                case 'database':
                    if (array_key_exists($key, self::$databaseConfig))
                        return self::$databaseConfig[$key];
                    break;
                default:
                    if (array_key_exists($key, self::$otherConfig))
                        return self::$otherConfig[$key];
            }

            // Nothing matched, return false.
            return false;
        }
        else
        {
            // Merge all settings.
            $configSettings = array_merge(self::$applicationConfig, self::$routerConfig, self::$exceptionConfig, self::$databaseConfig, self::$otherConfig);

            if (array_key_exists($key, $configSettings))
                return $configSettings[$key];
            else
                return false;
        }
    }

    /**
     * This function is used to delete a config setting.
     */
    public static function _deleteSetting($key, $context)
    {
        switch ($context)
        {
            case 'application':
                if (array_key_exists($key, self::$applicationConfig))
                {
                    unset(self::$applicationConfig[$key]);
                    return true;
                }
                break;
            case 'router':
                if (array_key_exists($key, self::$routerConfig))
                {
                    unset(self::$routerConfig[$key]);
                    return true;
                }
                break;
            case 'exception':
                if (array_key_exists($key, self::$exceptionConfig))
                {
                    unset(self::$exceptionConfig[$key]);
                    return true;
                }
                break;
            case 'database':
                if (array_key_exists($key, self::$databaseConfig))
                {
                    unset(self::$databaseConfig[$key]);
                    return true;
                }
                break;
            default:
                if (array_key_exists($key, self::$otherConfig))
                {
                    unset(self::$otherConfig[$key]);
                    return true;
                }
        }

        // Key not found, return false.
        return false;
    }
}
