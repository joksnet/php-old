<?php
/**
 * Aix Framework
 * Copyright (c) 2009, Juan M Mart('i)nez
 */

/**
 * @package Aix
 */
class Aix_Internal
{
    /**
     * Utilizado para devolver varios datos seg('u)n las opciones enviadas como
     * par('a)metro.
     *
     * @param integer $options
     * @param array $values
     * @return mixed
     */
    public static function options( $options = 0, $values = array() )
    {
        $return = array();
        $original = $options;

        ksort($values, SORT_NUMERIC);
        $values = array_reverse($values, true);

        foreach ( $values as $option => $value )
        {
            if ( 0 === $original || $options >= $option )
            {
                if ( is_array($value) )
                    $return = array_merge($return, $value);
                else array_push($return, $value);

                $options -= $option;
            }
        }

        if ( empty($return) )
            return false;
        elseif ( sizeof($return) == 1 )
            return array_pop($return);
        else
            return $return;
    }
}