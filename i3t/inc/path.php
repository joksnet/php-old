<?php

class Path
{
    public static $path = '';

    public static function join( $array )
    {
        settype($array, 'array');
        array_unshift($array, self::$path);

        return implode(DIRECTORY_SEPARATOR, $array);
    }

    public static function rawjoin( $array )
    {
        settype($array, 'array');
        return implode(DIRECTORY_SEPARATOR, $array);
    }

    public static function real( $spath )
    {
        $apath = explode('/', $spath);
        $array = array();

        settype($spath, 'string');
        settype($apath, 'array');

        foreach ( $apath as $cpath )
            array_push($array, $cpath);

        return self::join($array);
    }
}