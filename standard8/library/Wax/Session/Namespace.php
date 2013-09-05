<?php

include_once 'Wax/Session/Exception.php';

class Wax_Session_Namespace implements IteratorAggregate
{
    protected $_namespace = '';

    public function __construct( $namespace = 'Default' )
    {
        if ( strlen($namespace) == 0 )
        {
            throw new Wax_Session_Exception(
                "The session name '$namespace' must be a non-empty string"
            );
        }

        if ( strncmp($namespace, '__', 1) == 0 )
        {
            throw new Wax_Session_Exception(
                "The session name '$namespace' must not start with an underscore."
            );
        }

        if ( !( Wax_Session::isStarted() ) )
        {
            throw new Wax_Session_Exception(
                "Session must be started."
            );
        }

        $this->_namespace = $namespace;
    }

    public function &__get( $name )
    {
        if ( strlen($name) == 0 )
        {
            throw new Wax_Session_Exception(
                "The key must be a non-empty string"
            );
        }

        return $_SESSION[$this->_namespace][$name];
    }

    public function __set( $name, $value )
    {
        if ( strlen($name) == 0 )
        {
            throw new Wax_Session_Exception(
                "The key must be a non-empty string"
            );
        }

        $_SESSION[$this->_namespace][$name] = $value;
    }

    public function __isset( $name )
    {
        if ( strlen($name) == 0 )
        {
            throw new Wax_Session_Exception(
                "The key must be a non-empty string"
            );
        }

        return isset($_SESSION[$this->_namespace][$name]);
    }

    public function __unset( $name )
    {
        if ( strlen($name) == 0 )
        {
            throw new Wax_Session_Exception(
                "The key must be a non-empty string"
            );
        }

        unset($_SESSION[$this->_namespace][$name]);
    }

    public function get( $name ) { return $this->__get($name); }
    public function set( $name, $value ) { $this->__set($name, $value); }
    public function has( $name ) { return $this->__isset($name); }

    public function add( $name, $value ) { $this->__set($name, $value); }
    public function remove( $name ) { return $this->__unset($name); }

    public function count()
    {
        return sizeof($_SESSION[$this->_namespace]);
    }

    public function clear()
    {
        # if ( isset($_SESSION[$this->_namespace]) )
        #     unset($_SESSION[$this->_namespace]);

        if ( isset($_SESSION[$this->_namespace]) && is_array($_SESSION[$this->_namespace]) )
            foreach ( $_SESSION[$this->_namespace] as $name => $value )
                unset($_SESSION[$this->_namespace][$name]);
    }

    public function getIterator()
    {
        if ( isset($_SESSION[$this->_namespace]) && is_array($_SESSION[$this->_namespace]) )
            return new ArrayObject($_SESSION[$this->_namespace]);
        else
            return new ArrayObject( array() );
    }
}