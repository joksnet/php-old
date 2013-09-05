<?php

include_once 'Wax/Session/Exception.php';
include_once 'Wax/Session/Namespace.php';

final class Wax_Session
{
    private static $_sessionStarted = false;
    private static $_sessionDestroyed = false;

    public static function start()
    {
        if ( self::$_sessionStarted )
            return;

        if ( headers_sent( $file, $line ) )
        {
            throw new Wax_Session_Exception(
                "Session must be started before any output has been sent to"
              . " the browser; output started in {$file}:{$line}"
            );
        }

        if ( defined('SID') )
        {
            throw new Wax_Session_Exception(
                'Session has already been started by session.auto-start'
              . ' or session_start()'
            );
        }

        self::$_sessionStarted = session_start();
    }

    public static function destroy()
    {
        self::$_sessionDestroyed = session_destroy();
    }

    public static function isStarted()
    {
        return self::$_sessionStarted;
    }

    public static function isDestroyed()
    {
        return self::$_sessionDestroyed;
    }

    public static function getId()
    {
        return session_id();
    }

    public static function setId( $newId )
    {
        if ( !( is_string($newId) ) || $newId === '' )
        {
            throw new Wax_Session_Exception(
                'You must provide a non-empty string as a session identifier.'
            );
        }

        if ( !( self::$_sessionStarted ) )
            session_id($newId);
    }

    public static function getIterator()
    {
        $namespaces = array();

        if ( isset($_SESSION) )
        {
            $namespaces = array_keys($_SESSION);

            foreach ( $namespaces as $key => $value )
            {
                if ( strncmp($key, '__', 2) == 0 || !( is_array($_SESSION[$value]) ) )
                    unset($namespaces[$key]);
            }
        }

        return new ArrayObject($namespaces);
    }
}