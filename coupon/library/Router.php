<?php

class Router
{
    protected $routes = array();

    /**
     * @param array $routes
     */
    public function __construct( $routes )
    {
        $this->routes = $routes;
    }

    public function dispatch()
    {
        $response = Response::getInstance();

        $url = new Url();
        $uri = $url->getPath();

        foreach ( array_reverse($this->routes, true) as $route => $class )
        {
            if ( preg_match("~^$route$~", $uri, $params) )
            {
                $return = call_user_func_array(
                    array('Controller', 'dispatch'),
                    array_merge(
                        array($class),
                        array_slice($params, 1)
                    )
                );

                if ( !( false === $return ) )
                {
                    //$response->setHeader('Content-Type', 'application/xhtml+xml');
                    $response->setHeader('Content-Type', 'text/html;charset=UTF-8');
                    $response->setBody($class, $return);

                    return true;
                }
            }
        }

        if ( $response->getHttpResponseCode() == 200 )
        {
            //$response->setHeader('HTTP/1.0 404 Not Found');
            $response->setHttpResponseCode(404);
            $response->setBody('404', 'Error 404');
        }

        return false;
    }
}