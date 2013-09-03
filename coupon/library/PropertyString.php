<?php

class PropertyString extends Property
{
    public function __construct( $length = null, $default = '' )
    {
        parent::__construct();

        if ( !( is_integer($length) ) )
            throw new Exception('Property length must be integer.');

        if ( 0 >= $length )
            throw new Exception('Property length must be grater than zero.');

        if ( !( is_string($default) ) )
            throw new Exception('Propery default value must be string.');

        $this->length  = $length;
        $this->default = $default;
    }

    protected function name()
    {
        if ( null === $this->length )
            return 'TEXT';

        return 'VARCHAR';
    }

    public function validate( $value )
    {
        settype($value, 'string');

        if ( !( is_string($value) ) )
            throw new Exception('Value must be a string.');

        return $value;
    }
}