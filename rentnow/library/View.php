<?php

class View
{
    protected $class = '';
    protected $params = array();

    public function __construct( $class, array $params = array() )
    {
        $this->class = $class;
        $this->params = $params;
    }

    public function __get( $name )
    {
        if ( isset($this->params[$name]) )
            return $this->params[$name];

        return null;
    }

    public function __isset( $name )
    {
        return isset($this->params[$name]);
    }

    public function dispatch()
    {
        ob_start();

        require 'views/default/' . str_replace('_', '/', $this->class) . '.phtml';
        return ob_get_clean();
    }
}