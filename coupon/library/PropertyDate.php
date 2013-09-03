<?php

class PropertyDate extends Property
{
    const FORMAT = 'Y-m-d H:i:s';

    protected $autoUpdate = false;
    protected $autoInsert = false;

    public function __construct( $autoUpdate = false, $autoInsert = false )
    {
        parent::__construct();

        $this->autoUpdate = $autoUpdate;
        $this->autoInsert = $autoInsert;

        if ( $this->autoUpdate )
            $this->default = date(self::FORMAT);
    }

    protected function name()
    {
        return 'DATETIME';
    }

    public function validate( $value )
    {
        if ( is_integer($value) )
        {
            if ( 0 >= $value )
                throw new Exception('Timestamp must be grater than zero.');

            return date(self::FORMAT, $value);
        }

        throw new Exception('Date is not a recognizable format.');
    }
}