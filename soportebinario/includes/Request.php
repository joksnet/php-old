<?php

/**
 * @author Juan M Martinez <joksnet@gmail.com>
 */
class Request
{
    /**
     * @return boolean
     */
    public static function isPost()
    {
        return ( sizeof($_POST) > 0 );
    }

    /**
     * @param string $var
     * @param mixed $default
     * @return mixed
     */
    public static function getPost( $var, $default = null )
    {
        return ( isset($_POST[$var]) ) ? $_POST[$var] : $default;
    }

    /**
     * @param string $var
     * @return boolean
     */
    public static function hasPost( $var )
    {
        return ( isset($_POST[$var]) );
    }

    /**
     * @param string $var
     * @param mixed $default
     * @return mixed
     */
    public static function getQuery( $var, $default = null )
    {
        return ( isset($_GET[$var]) ) ? $_GET[$var] : $default;
    }

    /**
     * @param string $var
     * @return boolean
     */
    public static function hasQuery( $var )
    {
        return ( isset($_GET[$var]) );
    }

    /**
     * @param string $var
     * @param integer $default
     * @return integer
     */
    public static function getQueryInteger( $var, $default = 0 )
    {
        if ( self::hasQuery($var) )
            return intval( self::getQuery($var, $default) );

        return intval( $default );
    }
}