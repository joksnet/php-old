<?php
/**
 * Aix Framework
 * Copyright (c) 2009, Juan M Mart('i)nez
 */

/**
 * @see Aix_Config
 */
require_once 'Aix/Config.php';

/**
 * @see Aix_Db_Exception
 */
require_once 'Aix/Db/Exception.php';

/**
 * @see Aix_Db_Adapter
 */
require_once 'Aix/Db/Adapter.php';

/**
 * @package Aix_Db
 */
class Aix_Db
{
    /**
     * Fabrica clases `Aix_Db_Adapter`.
     *
     * @param string $adapter
     * @param array|Aix_Config $config
     * @return Aix_Db_Adapter
     */
    public static function factory( $adapter, $config )
    {
        if ( !( is_string($adapter) ) )
            throw new Aix_Db_Exception('Adapter must be a string');

        if ( !( is_array($config) ) && !( $config instanceof Aix_Config ) )
            throw new Aix_Db_Exception('Config must be an an instance of `Aix_Config` or an array');

        $adapterName = 'Aix_Db_Adapter_' . str_replace(' ', '_' , ucwords(str_replace('_', ' ', $adapter)));

        if ( !( class_exists($adapterName) ) )
            Aix_Loader::loader($adapterName);

        $adapter = new $adapterName($config);

        if ( !( $adapter instanceof Aix_Db_Adapter ) )
            throw new Aix_Db_Exception("Adapter class \"$adapterName\" must be an instance of `Aix_Db_Adapter`");

        return $adapter;
    }
}