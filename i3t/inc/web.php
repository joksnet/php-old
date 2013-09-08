<?php

class Web
{
    public static function getRequestUri()
    {
        return $_SERVER['REQUEST_URI'];
    }

    public static function getIP()
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    public static function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    public static function dispatch( $urls )
    {
        $found = false;
        $requestUri = self::getRequestUri();
        $isPost = self::isPost();

        foreach ( $urls as $url => $name )
        {
            if ( preg_match("~$url~", $requestUri, $params) )
            {
                array_shift($params);

                if ( is_readable('bin/' . $name . '.php') )
                {
                    include_once 'bin/' . $name . '.php';

                    $found = true;
                    $controller = new $name($params);

                    if ( $controller->type && !( empty($controller->type) ) )
                        header('Content-Type: ' . $controller->type);

                    if ( method_exists($controller, '__toString') )
                        echo $controller->__toString();
                    break;
                }
            }
        }

        if ( !( $found ) )
        {
            header('HTTP/1.0 404 Not Found');
        }
    }
}