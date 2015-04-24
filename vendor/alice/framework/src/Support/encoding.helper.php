<?php

if (!function_exists('ent'))
{
    /**
     * This method is used to escape a string with htmlentities.
     *
     * @param string $string The string to escape.
     * @return string        The escaped string
     */
    function ent($string)
    {
        return htmlentities($string, ENT_QUOTES, 'UTF-8', false);
    }
}
