<?php

class Request
{
    public static function get( $key )
    {
        switch ( true )
        {
            case isset( $_GET[$key] ):
                return $_GET[$key];

            case isset( $_POST[$key] ):
                return $_POST[$key];

            case isset( $_COOKIE[$key] ):
                return $_COOKIE[$key];

            case ( $key == 'REQUEST_URI' ):
                return self::getRequestUri();

            case isset( $_SERVER[$key] ):
                return $_SERVER[$key];

            case isset( $_ENV[$key] ):
                return $_ENV[$key];

            default:
                return null;
        }
    }

    public static function has( $key )
    {
        switch ( true )
        {
            case isset( $_GET[$key] ):
                return true;

            case isset( $_POST[$key] ):
                return true;

            case isset( $_COOKIE[$key] ):
                return true;

            case isset( $_SERVER[$key] ):
                return true;

            case isset( $_ENV[$key] ):
                return true;

            default:
                return false;
        }
    }

    public static function hasQuery( $key )
    {
        if ( isset($_GET[$key]) )
            return true;

        return false;
    }

    public static function hasPost( $key )
    {
        if ( isset($_POST[$key]) )
            return true;

        return false;
    }

    public static function getQuery( $key = null, $default = null )
    {
        if ( null === $key )
            return $_GET;

        return ( isset($_GET[$key]) ) ? $_GET[$key] : $default;
    }

    public static function getPost( $key = null, $default = null )
    {
        if ( null === $key )
            return $_POST;
        elseif ( is_array($key) )
        {
            $var = $_POST;

            foreach ( $key as $each )
            {
                if ( isset($var[$each]) )
                    $var = $var[$each];
                else
                    return $default;
            }

            return $var;
        }

        return ( isset($_POST[$key]) ) ? $_POST[$key] : $default;
    }

    public static function getCookie( $key = null, $default = null )
    {
        if ( null === $key )
            return $_COOKIE;

        return ( isset($_COOKIE[$key]) ) ? $_COOKIE[$key] : $default;
    }

    public static function getServer( $key = null, $default = null )
    {
        if ( null === $key )
            return $_SERVER;

        return ( isset($_SERVER[$key]) ) ? $_SERVER[$key] : $default;
    }

    public static function getEnv( $key = null, $default = null )
    {
        if ( null === $key )
            return $_ENV;

        return ( isset($_ENV[$key]) ) ? $_ENV[$key] : $default;
    }

    public static function getRequestUri()
    {
        if ( isset($_SERVER['HTTP_X_REWRITE_URL']) )
            $requestUri = $_SERVER['HTTP_X_REWRITE_URL'];
        elseif ( isset($_SERVER['REQUEST_URI']) )
            $requestUri = $_SERVER['REQUEST_URI'];
        elseif (isset($_SERVER['ORIG_PATH_INFO']))
        {
            $requestUri = $_SERVER['ORIG_PATH_INFO'];

            if ( !( empty($_SERVER['QUERY_STRING']) ) )
                $requestUri .= '?' . $_SERVER['QUERY_STRING'];
        }

        return $requestUri;
    }

    public static function getMethod()
    {
        return self::getServer('REQUEST_METHOD');
    }

    public static function isPost()
    {
        if ( 'POST' == self::getMethod() )
            return true;

        return false;
    }

    public static function isXmlHttpRequest()
    {
        return ( self::getHeader('X_REQUESTED_WITH') == 'XMLHttpRequest' );
    }

    public static function getHeader( $header )
    {
        if ( empty($header) )
            return false;

        $temp = 'HTTP_' . strtoupper(str_replace('-', '_', $header));

        if ( !( empty($_SERVER[$temp]) ) )
            return $_SERVER[$temp];

        if ( function_exists('apache_request_headers') )
        {
            $headers = apache_request_headers();

            if ( !( empty($headers[$header]) ) )
                return $headers[$header];
        }

        return false;
    }
}