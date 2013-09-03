<?php

class PropertyIdentifier extends Property
{
    public function __construct()
    {
        parent::__construct();

        $this->length = 8;
        $this->extras = 'AUTO_INCREMENT PRIMARY KEY';
    }

    protected function name()
    {
        return 'MEDIUMINT';
    }

    public function validate( $value )
    {
        settype($value, 'interger');

        if ( !( is_integer($value) ) )
            throw new Exception('Identifier must be integer.');

        if ( 0 >= $value )
            throw new Exception('Identifier must be grater than zero.');

        return $value;
    }
}