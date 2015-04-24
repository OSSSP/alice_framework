<?php

if (!function_exists('startsWith'))
{
    /**
     * This method is used to check if a string starts with a particular substring.
     *
     * @param string $haystack The string to check.
     * @param string $needle   The substring to search for.
     * @return bool            True if $haystack starts with $needle, False otherwise.
     */
    function startsWith($haystack, $needle)
    {
        return strrpos($haystack, $needle, -strlen($haystack)) === 0;
    }
}

if (!function_exists('endsWith'))
{
    /**
     * This method is used to check if a string ends with a particular substring.
     *
     * @param string $haystack The string to check.
     * @param string $needle   The substring to search for.
     * @return bool            True if $haystack ends with $needle, False otherwise.
     */
    function endsWith($haystack, $needle)
    {
        return ($needle === '') ? false : (($offset = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $offset) !== FALSE);
    }
}

// TODO: implement contains, uppercase, lowercase, truncateWith, matches, append, randomString
