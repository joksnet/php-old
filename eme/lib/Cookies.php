<?php

class Cookies
{
    public static $prefix = '';

    public static function set( $name, $value, $expires )
    {
        setcookie(
            self::$prefix . $name, $value, time() + intval($expires), '/'
        );
    }

    public static function get( $name )
    {
        if ( isset($_COOKIE[self::$prefix . $name]) )
            return $_COOKIE[self::$prefix . $name];
        else
            return false;
    }

    public static function del( $name )
    {
        setcookie(
            self::$prefix . $name, '', time() - 3600, '/'
        );
    }
}