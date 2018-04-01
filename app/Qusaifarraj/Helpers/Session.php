<?php

namespace Qusaifarraj\Helpers;


/**
 * Session class that manages the $_SESSION variable
 */
class Session
{
    /**
     * sets session value with name\key
     */
    public static function set($name, $value)
    {
        return $_SESSION[$name] = $value;
    }

    /**
     * returns true if session[key] exists
     */
    public static function exists($key)
    {
        return (isset($_SESSION[$key])) ? true : false;
    }

    /**
     * gets value of session[key]
     */
    public static function get($key, $default = null)
    {
        if(self::exists($key)) {
            return $_SESSION[$key];
        }
        return $default;
    }

    /**
     * unsets session[key]
     */
    public static function delete($key)
    {
        if(self::exists($key)) {
            unset($_SESSION[$key]);
        }
    }
}