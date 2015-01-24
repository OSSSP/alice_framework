<?php namespace Alice\Config;

use Alice\Core\Application;

class ConfigReaderFiles extends ConfigReader
{
    public static function init()
    {
        self::loadConfigFromFiles();
    }

    private static function parseFile($file)
    {
        // At this point filenames will always end with .config.php
        $masterConfigKey = strstr($file, '.config.php', true);

        $settings = require Application::getPath('path.config') . DIRECTORY_SEPARATOR . $file;

        foreach ($settings as $key => $value)
        {
            parent::_addSetting($key, $value, $masterConfigKey);
        }
    }

    private static function loadConfigFromFiles()
    {
        // Select application/config directory to scan for config files.
        $dir = new \DirectoryIterator(Application::getPath('path.config'));

        foreach ($dir as $fileInfo)
        {
            if (!$fileInfo->isDot())
            {
                // Parse only files that ends with .config.php

                $fileName = $fileInfo->getFilename();
                $configMark = '.config.php';

                if (strlen($configMark) < strlen($fileName))
                {
                    if (substr_compare($fileName, $configMark, strlen($fileName) - strlen($configMark), strlen($configMark)) === 0)
                    {
                        self::parseFile($fileName);
                    }
                }
            }
        }
    }

    public static function getSetting($key, $enable_context_search = true)
    {
        return parent::_getSetting($key, $enable_context_search);
    }

    public static function addSetting($key, $value)
    {
        // Attempt to separate context from key.
        $context = strstr($key, '.', true);
        if ($context)
            $key = substr($key, strlen($context) + 1);

        parent::_addSetting($key, $value, $context);
    }

    public static function deleteSetting($key)
    {
        // Attempt to extract context.
        $context = strstr($key, '.', true);

        return parent::_deleteSetting($key, $context);
    }
}
