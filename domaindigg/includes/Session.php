<?php

define('CAPTCHA_LENGTH', 5);

class Session
{
    private static $name = 'dd';

    protected static $sid = null;
    protected static $data = null;

    public static $logged = false;

    /**
     * Begins the session.
     */
    public static function start()
    {
        session_start();

        if ( isset($_SESSION[self::$name . '_SID']) )
        {
            self::$sid  = $_SESSION[self::$name . '_SID'];
            self::$data = $_SESSION[self::$name . '_DATA'];
        }
        else
        {
            self::$sid = md5( time() );
            self::$data = array(
                'lang' => Lang::current(),
                'uid'  => 0
            );

            $_SESSION[self::$name . '_SID'] = self::$sid;
            $_SESSION[self::$name . '_DATA'] = self::$data;
        }

        Lang::load( self::_('lang') );

        if ( self::$data['uid'] )
            self::$logged = true;
    }

    /**
     * Register a new session data.
     *
     * @param array $data
     */
    public static function register( $data )
    {
        foreach ( $data as $key => $value )
            self::$data[$key] = $value;

        $_SESSION[self::$name . '_DATA'] = self::$data;
    }

    public static function destroy()
    {
        $_SESSION[self::$name . '_DATA'] = array(
            'lang' => self::$data['lang'],
            'uid'  => 0
        );
    }

    /**
     * Return data from the session or false.
     *
     * @param string $key
     * @return mixed
     */
    public static function _( $key )
    {
        if ( isset(self::$data[$key]) )
            return self::$data[$key];

        return false;
    }

    public static function captcha( $key, $value = null )
    {
        if ( !( isset($_SESSION[self::$name . '_CAPTCHA']) ) )
            $_SESSION[self::$name . '_CAPTCHA'] = array();

        if ( !( empty($value) ) )
            $_SESSION[self::$name . '_CAPTCHA'][$key] = $value;

        return $_SESSION[self::$name . '_CAPTCHA'][$key];
    }

    public static function captchaCompare( $key, $captcha )
    {
        return ( strtolower( self::captcha($key) ) == strtolower( $captcha ) ) ? true : false;
    }
}