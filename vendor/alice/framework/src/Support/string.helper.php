<?php

if (!function_exists('startsWith'))
{
    function startsWith($haystack, $needle)
    {
        return strrpos($haystack, $needle, -strlen($haystack)) === 0;
    }
}

if (!function_exists('endsWith'))
{
    function endsWith($haystack, $needle)
    {
        return ($needle === '') ? false : (($offset = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $offset) !== FALSE);
    }
}

// TODO: implement contains, uppercase, lowercase, truncateWith, matches, append, randomString
