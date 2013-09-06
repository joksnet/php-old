<?php

class String
{
    public static function filter( $text )
    {
        static $search  = array('á', 'Á', 'é', 'É', 'í', 'Í', 'ó', 'Ó', 'ú', 'Ú', 'ñ', 'Ñ');
        static $replace = array('a', 'A', 'e', 'E', 'i', 'I', 'o', 'O', 'u', 'U', 'n', 'N');

        return str_replace($search, $replace, $text);
    }

    public static function entities( $text )
    {
        static $entities = array(
            '&' => 'amp',
            '«' => 'laquo',
            '»' => 'raquo',
            'á' => 'aacute',
            'Á' => 'Aacute',
            'é' => 'eacute',
            'É' => 'Aacute',
            'í' => 'iacute',
            'Í' => 'Iacute',
            'ó' => 'oacute',
            'Ó' => 'Oacute',
            'ú' => 'uacute',
            'Ú' => 'Uacute',
            'ñ' => 'ntilde',
            'Ñ' => 'Ntilde',
            '¿' => 'iquest'
        );

        foreach ( $entities as $letter => $entity )
            $text = str_replace($letter, "&$entity;", $text);

        return $text;
    }

    public static function htmlentities( $text )
    {
        return htmlentities($text, null, 'UTF-8');
    }

    public static function format( $format, $values = array() )
    {
        settype($values, 'array');

        if ( func_num_args() > 2 )
            $values = array_slice( func_get_args(), 1 );

        if ( strpos($format, '{') === false && strpos($format, '}') === false )
            $format = "\{$format}";

        if ( preg_match_all('/{([^{^}]*)}/', $format, $matches) )
        {
            $return  = '';
            $matches = array_pop($matches);

            foreach ( $matches as $i => $match )
            {
                if ( !( isset($values[$i]) ) )
                    continue;

                $value = $values[$i];

                if ( strlen($value) > 0 )
                    $return .= sprintf($match, $value);
            }

            return $return;
        }

        return false;
    }
}

function e( $string )
{
    $params = array();

    if ( func_num_args() > 1 )
        $params = array_slice( func_get_args(), 1 );

    $string = String::entities($string);
    # $string = String::htmlentities($string);

    if ( sizeof($params) > 0 )
    {
        echo String::format($string, $params);
    }
    else
        echo $string;
}