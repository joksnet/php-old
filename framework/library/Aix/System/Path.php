<?php
/**
 * Aix Framework
 * Copyright (c) 2009, Juan M Mart('i)nez
 */

/**
 * @see Aix_Internal
 */
require_once 'Aix/Internal.php';

/**
 * @package Aix_System
 */
class Aix_System_Path
{
    /**
     * Separador de rutas.
     *
     * @var string
     */
    public static $separator = DIRECTORY_SEPARATOR;

    /**
     * Junta rutas de manera inteligente, utilizando `System_Path::$separator`
     * como pegamento.
     *
     * @param string $path1
     * @return string
     */
    public static function join( $path1 )
    {
        return implode(
            Aix_System_Path::$separator, func_get_args()
        );
    }

    /**
     * Devuelve la ruta can('o)nica y absoluta. Esta funci('o)n busca en la
     * variable `INCLUDE_PATH`, cuando la funci('o) de PHP no.
     *
     * @param string $path
     * @return string
     */
    public static function real( $path )
    {
        if ( $real = realpath($path) )
            return $real;

        foreach ( Aix_System::path() as $include )
        {
            if ( $include == '.' )
            {
                if ( $real = realpath($path) )
                    return $real;

                continue;
            }

            $file = Aix_System_Path::join($include, $path);

            if ( $real = realpath($file) )
                return $real;
        }

        return false;
    }

    /**
     * Devuelve informaci('o)n sobre la ruta. La matriz `$options` puede
     * ser `PATHINFO_DIRNAME`, `PATHINFO_BASENAME`, `PATHINFO_EXTENSION`,
     * `PATHINFO_FILENAME` o cualquier suma entre ellos.
     *
     * @param string $path
     * @return array
     */
    public static function info( $path, $options = 0 )
    {
        $extension = '';

        if ( strpos( $path, Aix_System_Path::$separator ) !== false )
        {
            $basenameParts = explode( Aix_System_Path::$separator, $path );
            $basename = $filename = end($basenameParts);
        }

        $dirname = rtrim( substr( $path, 0, strlen($path) - strlen($basename) ), Aix_System_Path::$separator );

        if ( strpos($basename, '.') !== false )
        {
            $extensionParts = explode('.', $basename);
            $extension = end($extensionParts);
            $filename = substr($basename, 0, strlen($basename) - strlen($extension) - 1);
        }

        return Aix_Internal::options($options, array(
            PATHINFO_DIRNAME   => array('dirname'   => $dirname),
            PATHINFO_BASENAME  => array('basename'  => $basename),
            PATHINFO_EXTENSION => array('extension' => $extension),
            PATHINFO_FILENAME  => array('filename'  => $filename)
        ));
    }
}