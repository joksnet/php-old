<?php

include_once 'Wax/Factory/Exception.php';

final class Wax_Factory
{
    public static function createObject( $className )
    {
        $args = func_get_args();

        $moduleName = '';
        $className = array_shift($args);

        if ( is_array($className) )
        {
            $moduleName = array_shift($className);
            $className = array_shift($className);
        }

        self::includeClass($className, $moduleName);

        if ( sizeof($args) > 0 )
        {
            if ( sizeof($args) == 1 && is_array($args[0]) )
                $args = array_shift($args);

            $reflection = new ReflectionClass($className);
            $output = call_user_func_array(array($reflection, 'newInstance'), $args);

            return $output;
        }
        else
            return new $className();
    }

    public static function includeClass( $className, $moduleName = '' )
    {
        if ( preg_match('/[^a-z0-9\-_.]/i', $className) )
        {
            throw new Wax_Factory_Exception(
                'Security check: Illegal character in filename'
            );
        }

        if ( class_exists($className, false) || interface_exists($className, false) )
            return;

        $fileName = str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

        if ( strlen($moduleName) > 0 )
            $fileName = $moduleName . DIRECTORY_SEPARATOR . $fileName;

        if ( self::isReadable($fileName) )
        {
            $return = include_once $fileName;

            if ( !( class_exists($className, false) || interface_exists($className, false) ) )
            {
                throw new Wax_Factory_Exception(
                    "File \"$fileName\" was loaded but class \"$className\""
                  . " was not found in the file"
                );
            }
            else
                return $return;
        }
        else
        {
            throw new Wax_Factory_Exception(
                "File \"$fileName\" was not found"
            );
        }
    }

    public static function isReadable( $fileName )
    {
        if ( is_readable($fileName) )
            return true;

        $path = get_include_path();
        $dirs = explode(PATH_SEPARATOR, $path);

        foreach ( $dirs as $dir )
        {
            if ( '.' == $dir )
                continue;

            if ( is_readable($dir . DIRECTORY_SEPARATOR . $fileName) )
                return true;
        }

        return false;
    }
}
