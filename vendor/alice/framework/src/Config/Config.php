<?php namespace Alice\Config;

class Config extends ConfigReaderFiles implements ConfigInterface
{
    public static function load()
    {
        parent::init();
    }

    public static function get($setting, $enable_context_search = true)
    {
        return parent::getSetting($setting, $enable_context_search);
    }

    public static function set($key, $value)
    {
        parent::addSetting($key, $value);
    }

    public static function delete($key)
    {
        return parent::deleteSetting($key);
    }
}
