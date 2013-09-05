<?php

class Lang
{
    public static function __( $text )
    {
        return $text;
    }

    public static function e( $text )
    {
        e( self::__($text) );
    }
}