<?php

final class Standard8_Uri
{
    protected static $__init = false;

    protected static $_uriScheme = '';
    protected static $_uriHost = '';

    public static function __init()
    {
        self::$__init = true;

        self::$_uriScheme = Standard8_Config::getInstance()->uriScheme;
        self::$_uriHost = Standard8_Config::getInstance()->uriHost;
    }

    public static function createUri( $className = null, $moduleName = null )
    {
        $uriScheme = Standard8_Config::getInstance()->uriScheme;
        $uriHost = Standard8_Config::getInstance()->uriHost;

        if ( !( empty($moduleName) ) )
        {
            if ( strtolower($moduleName) != 'standard8' )
                $moduleName = strtolower($moduleName) . '.';
            else
                $moduleName = '';
        }
        else
            $moduleName = '';

        if ( !( empty($className) ) )
            $path = strtolower( str_replace('_', '/', $className) ) . '/';
        else
            $path = '';

        $sid = '?SID=' . Standard8_Session::getSID();

        return "$uriScheme://$moduleName$uriHost/$path$sid";
    }

    public static function createUriIcon( $icon )
    {
        $uriScheme = Standard8_Config::getInstance()->uriScheme;
        $uriHost = Standard8_Config::getInstance()->uriHost;

        return "$uriScheme://$uriHost/@@/$icon/";
    }

    public static function createUriProfile( $username )
    {
        $uriScheme = Standard8_Config::getInstance()->uriScheme;
        $uriHost = Standard8_Config::getInstance()->uriHost;

        return "$uriScheme://$uriHost/~$username/";
    }
}