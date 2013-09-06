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

    public static function getIP()
    {
        // return ( !empty($HTTP_SERVER_VARS['REMOTE_ADDR']) ) ? $HTTP_SERVER_VARS['REMOTE_ADDR'] : ( ( !empty($HTTP_ENV_VARS['REMOTE_ADDR']) ) ? $HTTP_ENV_VARS['REMOTE_ADDR'] : $REMOTE_ADDR );

        if ( isset( $_SERVER ['HTTP_X_FORWARDED_FOR'] ) )
            $ip = $_SERVER ['HTTP_X_FORWARDED_FOR'];
        elseif ( isset( $_SERVER ['HTTP_VIA'] ) )
            $ip = $_SERVER ['HTTP_VIA'];
        elseif ( isset( $_SERVER ['REMOTE_ADDR'] ) )
            $ip = $_SERVER ['REMOTE_ADDR'];
        else
            $ip = '127.0.0.1' ;

        return $ip;
    }
}