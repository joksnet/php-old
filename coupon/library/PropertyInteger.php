<?php

class PropertyInterger extends Property
{
    public function __construct( $length, $default = 0 )
    {
        parent::__construct();

        if ( !( is_integer($length) ) )
            throw new Exception('Property length must be integer.');

        if ( 0 >= $length )
            throw new Exception('Property length must be grater than zero.');

        if ( !( is_integer($default) ) )
            throw new Exception('Property default must be integer.');

        $this->length  = $length;
        $this->default = $default;
    }

    protected function name()
    {
        if ( 1 == $this->length )
            return 'TINYINT';

        if ( 8 >= $this->length )
            return 'MEDIUMINT';

        return 'INT';
    }

    public function validate( $value )
    {
        settype($value, 'integer');

        if ( !( is_integer($value) ) )
            throw new Exception('Value must be integer.');

        return $value;
    }
}