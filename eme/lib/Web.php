<?php

class Web
{
    public static $match = null;
    public static $errors = array(
        404 => array( 'Status' => '404 Not Found' )
    );

    public static function dispatch( $urls )
    {
        $found = false;
        $requestUri = Request::getRequestUri(); // $_SERVER['REQUEST_URI'];

        foreach ( $urls as $url => $className )
        {
            if ( preg_match("~$url~", $requestUri, $args) )
            {
                array_shift($args);

                if ( $found = self::instance($className, $args) )
                    break;
            }
        }

        if ( !( $found ) )
            self::error(404);
    }

    public static function error( $errno )
    {
        if ( isset( self::$errors[$errno] ) )
        {
            Response::setHeader('Status', self::$errors[$errno]['Status']);

            if ( isset( self::$errors[$errno]['Class'] ) )
                self::instance( self::$errors[$errno]['Class'] );
            else
            {
                Response::setHeader('Content-Type', 'text/plain');
                Response::setBody( self::$errors[$errno]['Status'] );
            }
        }
    }

    public static function errorRegister( $errno, $className )
    {
        if ( isset( self::$errors[$errno] ) )
            self::$errors[$errno]['Class'] = $className;
    }

    public static function instance( $className, $args = array() )
    {
        global $root;

        settype($className, 'string');
        settype($args, 'array');

        $fileName = str_replace('_', DIRECTORY_SEPARATOR, $className);

        if ( !( is_readable("$root/bin/$fileName.php") ) )
            return false;

        include_once "$root/bin/$fileName.php";

        if ( !( class_exists($className, false) ) )
            return false;

        $reflection = new ReflectionClass($className);

        if ( $reflection->getParentClass()->getName() != 'WebBase' )
            return false;

        if ( null === self::$match )
            self::$match = $className;

        $controller = call_user_func( array(&$reflection, 'newInstance'), $args );

        if ( $controller->type && !( empty($controller->type) ) && Response::canSendHeaders() )
            Response::setHeader('Content-Type', $controller->type, true);

        if ( Request::isPost() && method_exists($controller, 'submit') )
            $controller->submit();

        if ( Response::canSendHeaders() )
            Response::sendResponse();

        if ( method_exists($controller, 'dispatch') )
            $controller->dispatch();

        return true;
    }
}