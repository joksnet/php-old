<?php

class Cookies
{
    public static function set( $name, $value, $expires )
    {
        global $config;

        setcookie(
            $config['cookiePrefix'] . $name,
            $value,
            time() + intval($expires),
            '/'
        );
    }

    public static function get( $name )
    {
        global $config;
        return $_COOKIE[$config['cookiePrefix'] . $name];
    }

    public static function del( $name )
    {
        global $config;

        setcookie(
            $config['cookiePrefix'] . $name, '', time() - 3600, '/'
        );
    }
}