<?php

class HelloWorld
{
    public $name = '';
    public $type = 'text/plain';

    public function __construct( $argv = array() )
    {
        $this->name = $argv[0];
    }

    public function __toString()
    {
        if ( empty($this->name) )
            return 'Hello World!';
        else
            return 'Hello ' . $this->name . '!';
    }
}