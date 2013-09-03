<?php

class PropertyBoolean extends Property
{
    public function __construct( $default = false )
    {
        parent::__construct();

        if ( !( is_bool($default) ) )
            throw new Exception('Property default must be boolean.');

        $this->default = $default;
    }

    protected function name()
    {
        return 'BOOL';
    }

    public function validate( $value )
    {
        settype($value, 'boolean');

        if ( !( is_bool($value) ) )
            throw new Exception('Value must be boolean.');

        return $value;
    }
}