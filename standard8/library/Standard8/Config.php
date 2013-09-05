<?php

final class Standard8_Config
{
    protected static $_instance = null;
    protected $_config = null;

    public function __construct()
    {
        $this->_config = Wax_Db::select()
            ->from(TABLE_CONFIGURACION)
            ->query()
            ->fetchPairs();
    }

    /**
     * @return Standard8_Config
     */
    public static function getInstance()
    {
        if ( is_null(self::$_instance) )
            self::$_instance = new self();

        return self::$_instance;
    }

    public function __get( $name ) { return $this->_config[$name]; }
    public function __isset( $name ) { return isset($this->_config[$name]); }

    public function get( $name ) { return $this->__get($name); }
    public function has( $name ) { return $this->__isset($name); }

    public function count() { return sizeof($this->_config); }
}