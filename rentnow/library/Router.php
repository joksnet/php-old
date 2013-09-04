<?php

class Router
{
    public static $current = null;

    protected static $routes = array();

    public static function add( $route, $class )
    {
        self::$routes[$route] = $class;
    }

    public static function has( $route )
    {
        return isset(self::$routes[$route]);
    }

    public static function dispatchHostname()
    {
        $default = Configuration::getInstance()->idioma;

        $server = explode('.', $_SERVER['SERVER_NAME']);
        $idioma = array_shift($server);

        if ( !( Translate::locale($idioma) ) )
            return $default;

        return true;
    }

    public static function dispatch()
    {
        $url = new Url();
        $uri = $url->getPath();

        foreach ( array_reverse(self::$routes, true) as $route => $class )
        {
            if ( preg_match("~^$route$~", $uri, $params) )
            {
                Router::$current = $class;

                $return = call_user_func_array(
                    array('Controller', 'dispatch'),
                    array_merge(
                        array($class),
                        array_slice($params, 1)
                    )
                );

                if ( !( false === $return ) )
                {
                    $vars = get_class_vars($class);
                    $type = 'text/html';

                    if ( isset($vars['type']) )
                        $type = $vars['type'];

                    # PHP >= 5.3
                    # if ( isset($class::$type) )
                    #     $type = $class::$type;

                    //Response::setHeader('Content-Type', 'application/xhtml+xml');
                    Response::setHeader('Content-Type', "$type;charset=UTF-8");
                    Response::setBody($class, $return);

                    return;
                }

                Router::$current = null;
            }
        }

        if ( Response::getHttpResponseCode() == 200 )
        {
            $class  = 'Error404';
            $return = Controller::dispatch($class);

            Response::setHeader('Content-Type', 'text/html;charset=UTF-8');
            Response::setBody($class, $return);

            //Response::setHeader('HTTP/1.0 404 Not Found');
            //Response::setHttpResponseCode(404);
            //Response::setBody('404', 'Error 404');
        }
    }
}
