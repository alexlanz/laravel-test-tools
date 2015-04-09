<?php namespace Krumer\Test\Tools\Utils;

class Str {

    /**
     * Determine if a string is contained within another string.
     *
     * @param  string $haystack
     * @param  string $needle
     * @return boolean
     */
    public static function contains($haystack, $needle)
    {
        if ($needle != '' && stripos($haystack, $needle) !== false)
        {
            return true;
        }

        return false;
    }

}