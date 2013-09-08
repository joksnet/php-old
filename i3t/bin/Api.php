<?php

class Api
{
    public $filename = '';
    public $className = '';
    public $instance = null;

    //
    // public $type = 'application/json';
    //

    public function __construct( $argv = array() )
    {
        if ( !( empty($argv[0]) ) )
        {
            $className = $this->name($argv[0]);
            $fileName = Path::join( array('api', $className) );

            if ( substr($fileName, -4) != '.php' )
                $fileName .= '.php';

            if ( is_readable($fileName) )
            {
                $this->filename = $fileName;
                $this->className = $className;
                $this->dispatch();
            }
        }
    }

    protected function name( $name )
    {
        $name = ucfirst($name);
        $new = ''; $flag = false;

        for ( $i = 0, $l = strlen($name); $i < $l; $i++ )
        {
            $char = substr($name, $i, 1);

            if ( $char == '.' || $char == '_' )
            {
                $char = '_';
                $flag = true;
            }
            elseif ( $flag )
            {
                $char = strtoupper($char);
                $flag = false;
            }

            $new .= $char;
        }

        return $new;
    }

    public function dispatch()
    {
        include_once $this->filename;

        if ( class_exists($this->className) )
        {
            $this->instance = new $this->className();
            $this->type = $this->instance->type;
        }
    }

    public function __toString()
    {
        if ( method_exists($this->instance, '__toString') )
            return $this->instance->__toString();
        else
            return '';
    }
}