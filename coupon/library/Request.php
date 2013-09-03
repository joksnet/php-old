<?php

class Request
{
    /**
     * @var Request
     */
    protected static $instance;

    /**
     * @return Request
     */
    public static function getInstance()
    {
        if ( !( self::$instance instanceof Request ) )
            self::$instance = new Request();

        return self::$instance;
    }

    public function __get( $key )
    {
        switch ( true )
        {
            case isset($_GET[$key]):
                return $_GET[$key];

            case isset($_POST[$key]):
                return $_POST[$key];

            default:
                return null;
        }
    }

    public function __isset( $key )
    {
        switch ( true )
        {
            case isset($_GET[$key]):
                return true;

            case isset($_POST[$key]):
                return true;

            default:
                return false;
        }
    }

    public function getQuery( $key = null, $default = null )
    {
        if ( null === $key )
            return $_GET;

        return ( isset($_GET[$key]) ) ? $_GET[$key] : $default;
    }

    public function hasQuery( $key )
    {
        return isset($_GET[$key]);
    }

    public function getPost( $key = null, $default = null )
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

    public function hasPost( $key )
    {
        return isset($_POST[$key]);
    }

    public function getUri()
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

    public static function isPost()
    {
        return 'POST' == $_SERVER['REQUEST_METHOD'];
    }
}