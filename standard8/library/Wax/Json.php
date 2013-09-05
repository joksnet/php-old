<?php

include_once 'Wax/Json/Encoder.php';

class Wax_Json
{
    public static function encode( $value )
    {
        if ( function_exists('json_encode') )
            return json_encode($value);

        return Wax_Json_Encoder::encode($value);
    }
}