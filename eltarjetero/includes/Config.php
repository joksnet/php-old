<?php

/**
 * @author Juan M Martinez <joksnet@gmail.com>
 */
class Config
{
    /**
     * @var array
     */
    protected static $config = null;

    /**
     * @param string $name
     * @return mixed
     */
    public static function get( $name )
    {
        if ( null === self::$config )
        {
            $data = Db::query(
                "SELECT c.name, c.value
                 FROM config c"
            );

            foreach ( (array) $data as $row )
                self::$config[$row['name']] = $row['value'];
        }

        if ( isset(self::$config[$name]) )
            return self::$config[$name];
        else
            return null;
    }
}