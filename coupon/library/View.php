<?php

class View
{
    protected $class = '';
    protected $params = array();

    /**
     * @param string $class
     * @param array $params
     */
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

    public function __toString()
    {
        ob_start();

        require 'application/views/' . str_replace('_', '/', $this->class) . '.phtml';
        return ob_get_clean();
    }
}