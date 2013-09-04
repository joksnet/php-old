<?php

class Session
{
    protected static $instance;
    protected static $started = false;

    protected $prefix = '';

    public static function getInstance()
    {
        if ( !( self::$instance instanceof Session ) )
            self::$instance = new Session();

        return self::$instance;
    }

    public function __construct()
    {
        Session::start();
    }

    public function __get( $name )
    {
        if ( !( self::__isset($name) ) )
            return null;

        $data = $_SESSION[$this->prefix . $name];
        $data = self::decode($data);

        return $data;
    }

    public function __set( $name, $value )
    {
        $value = self::encode($value);

        if ( self::__isset($name) )
            self::__unset($name);

        $_SESSION[$this->prefix . $name] = $value;
    }

    public function __isset( $name )
    {
        return ( isset($_SESSION[$this->prefix . $name]) );
    }

    public function __unset( $name )
    {
        unset($_SESSION[$this->prefix . $name]);
    }

    public function id()
    {
        return session_id();
    }

    public function toArray()
    {
        $sessions = array();

        $sessionPrefix = $this->prefix;
        $sessionPrefixLength = strlen($sessionPrefix);

        foreach ( $_SESSION as $name => $data )
        {
            if ( strncasecmp($name, $sessionPrefix, $sessionPrefixLength) == 0 )
                $name = substr($name, $sessionPrefixLength);

            $sessions[$name] = self::decode($data);
        }

        return $sessions;
    }

    protected static function encode( $data )
    {
        return base64_encode( serialize($data) );
    }

    protected static function decode( $data )
    {
        return unserialize( base64_decode($data) );
    }

    public static function start()
    {
        if ( !( self::$started ) )
            session_start();

        self::$started = true;
    }

    public static function destroy()
    {
        if ( self::$started )
            session_destroy();
    }
}