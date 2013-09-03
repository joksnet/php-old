<?php
/**
 * Aix Framework
 * Copyright (c) 2009, Juan M Mart('i)nez
 */

/**
 * @see Aix_System_Path
 */
require_once 'Aix/System/Path.php';

/**
 * @package Aix_System
 */
class Aix_System
{
    /**
     * Separador de rutas en el `INCLUDE_PATH`.
     *
     * @var string
     */
    public static $separator = PATH_SEPARATOR;

    /**
     * Devuelve el `INCLUDE_PATH` como un array.
     *
     * @return array
     */
    public static function path()
    {
        $path = get_include_path();

        if ( Aix_System::$separator == ':' )
            // On *nix systems, include_paths which include paths with a stream
            // schema cannot be safely explode'd, so we have to be a bit more
            // intelligent in the approach.
            $paths = preg_split('#:(?!//)#', $path);
        else
            $paths = explode(Aix_System::$separator, $path);

        return $paths;
    }

    /**
     * Devuelve `True` si `$filename` se puede leer. Esta funci('o)n utiliza
     * la variable `INLCUDE_PATH`, cuando la funci('o) de PHP no.
     *
     * @param string $filename
     * @return boolean
     */
    public static function isReadable( $filename )
    {
        if ( Aix_System_Path::real($filename) )
            return true;

        return false;
    }
}