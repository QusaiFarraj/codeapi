<?php

namespace Qusaifarraj\Helpers;


/**
 * Cookie class that manages the $_COOKIE value
 */
class Cookie
{
    /**
     * sets cookie[name] with value
     */
    public static function set($name, $value, $expires, $path='/', $domain=null, $secure = false, $httpOnly=false)
    {
        if(setcookie($name, $value, $expires, $path, $domain, $secure, $httpOnly)) {
            return true;
        }
        return false;
    }

    /**
     * checks if cookie[name] exist
     */
    public static function exists($name)
    {
        return (bool) (isset($_COOKIE[$name])) ? true : false;
    }

    /**
     * gets the cookie[name] vlaue
     */
    public static function get($name)
    {
        if(self::exists($name))
            return $_COOKIE[$name];
        return null;
    }

    

    /**
     * deletes cookie[name]
     */
    public static function destroy($name)
    {
        self::set($name, '', time() - 1);
    }
}
