<?php
/**
 * Aix Framework
 * Copyright (c) 2009, Juan M Mart('i)nez
 */

/**
 * @see Aix_Config_Exception
 */
require_once 'Aix/Config/Exception.php';

/**
 * @package Aix_Config
 */
class Aix_Config
{
    /**
     * @var array
     */
    protected $data = array();

    /**
     * @var boolean
     */
    protected $readonly = true;

    /**
     * Provee de una interfase para controlar propiedades en un array. Toda la
     * informaci('o)n es de solo lectura por defecto.
     *
     * @param array $data
     */
    public function __construct( array $data )
    {
        $this->data = array();
        $this->readonly = true;

        foreach ( $data as $key => $value )
        {
            if ( is_array($value) )
                $this->data[$key] = new self($value);
            else
                $this->data[$key] = $value;
        }
    }

    public function __get( $name )
    {
        if ( array_key_exists($name, $this->data) )
            return $this->data[$name];

        return null;
    }

    public function __set( $name, $value )
    {
        if ( $this->readonly )
            throw new Aix_Config_Exception('Configuration is read only');

        if ( is_array($value) )
            $this->data[$name] = new self($value);
        else
            $this->data[$name] = $value;
    }

    public function __isset( $name )
    {
        return isset($this->data[$name]);
    }

    public function __unset( $name )
    {
        if ( $this->readonly )
            throw new Aix_Config_Exception('Configuration is read only');

        unset($this->data[$name]);
    }

    public function __toString()
    {
        $return = array();

        foreach ( $this->data as $key => $value )
            $return[] = "`$key`:" . ( $value instanceof self ? $value->__toString() : "\"$value\"" );

        return '{' . implode(',', $return) . '}';
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $return = array();

        foreach ( $this->data as $key => $value )
            $return[$key] = ( $value instanceof self ) ? $value->toArray() : $value;

        return $return;
    }
}