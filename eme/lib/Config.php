<?php

class Config
{
    protected static $config = null;

    public static function get( $name )
    {
        if ( null === self::$config )
        {
            self::$config = Db::pairs(
                "SELECT c.nombre, c.valor
                 FROM configuracion c
                 ORDER BY c.nombre"
            );
        }

        if ( self::$config[$name] )
            return self::$config[$name];

        return false;
    }
}