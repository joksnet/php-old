<?php
/**
 * Aix Framework
 * Copyright (c) 2009, Juan M Mart('i)nez
 */

/**
 * @see Aix_System
 */
require_once 'Aix/System.php';

/**
 * @see Aix_Loader_Exception
 */
require_once 'Aix/Loader/Exception.php';

/**
 * @package Aix_Loader
 */
class Aix_Loader
{
    /**
     * Carga el archivo en donde se encuentre la clase `$class`. Deduce su
     * ubicaci('o)n por el nombre de la clase. En donde `Some_Class` se
     * convierte en `Some/Class.php`.
     *
     * @param string $class
     * @return boolean
     */
    public static function loader( $class, $once = true )
    {
        if ( class_exists($class, false) || interface_exists($class, false) )
            return false;

        $file = str_replace('_', Aix_System_Path::$separator, $class) . '.php';

        if ( $once )
            require_once $file;
        else
            require $file;

        if ( !( class_exists($class, false) ) && !( interface_exists($class, false) ) )
            throw new Aix_Loader_Exception("File \"$file\" does not exist or class \"$class\" was not found in the file");

        return true;
    }

    /**
     * Registra el `AutoLoad` utilizando esta clase.
     */
    public static function register()
    {
        spl_autoload_register(
            array('Aix_Loader', 'loader')
        );
    }
}