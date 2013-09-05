<?php

class Validation
{
    public static function check( $rules )
    {
        $result = array();
        $valid  = true;

        foreach ( $rules as $varName => $varRules )
        {
            $result[$varName] = array();

            if ( is_string($varRules) )
            {
                $rulesTmp = explode(' ', $varRules);
                $trues = array_fill(0, sizeof($rulesTmp), true);

                $varRules = array_combine($rulesTmp, $trues);
            }

            foreach ( $varRules as $ruleName => $param )
            {
                $var = Request::get($varName);

                if ( is_array($var) )
                {
                    foreach ( $var as $key => $value )
                    {
                        $bool = $result[$varName][$ruleName] = self::checkRule( $ruleName, $param, $value );

                        if ( !( $bool ) )
                            $valid = false;
                    }
                }
                else
                {
                    $bool = $result[$varName][$ruleName] = self::checkRule( $ruleName, $param, $var );

                    if ( !( $bool ) )
                        $valid = false;
                }
            }
        }

        return array($valid, $result);
    }

    protected static function checkRule( $rule, $param, $var )
    {
        if ( method_exists('Validation', $rule) )
        {
            if ( $param === true )
                return self::$rule( $var );
            else
                return self::$rule( $var, $param );
        }

        return true;
    }

    /**************************************************************************/

    protected static function required( $var )
    {
        return !( empty($var) );
    }

    protected static function minLength( $var, $minLength )
    {
        return ( strlen($var) >= $minLength );
    }

    protected static function maxLength( $var, $maxLength )
    {
        return ( strlen($var) <= $maxLength );
    }

    protected static function rangeLength( $var, $range )
    {
        return ( strlen($var) >= $range[0] && strlen($var) <= $range[1] );
    }

    protected static function minValue( $var, $minValue )
    {
        return ( $var >= $minValue );
    }

    protected static function maxValue( $var, $maxValue )
    {
        return ( $var <= $maxValue );
    }

    protected static function rangeValue( $var, $range )
    {
        return ( $var >= $range[0] && $var <= $range[1] );
    }

    protected static function email( $var )
    {
        return preg_match('/^[^\s,;]+@([^\s.,;]+\.)+[\w-]{2,}$/', $var);
    }

    protected static function url( $var )
    {
        return preg_match("/^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)*(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/", $var);
    }

    protected static function date( $var )
    {
        return true;
    }

    protected static function dateISO( $var )
    {
        return preg_match('/^\d{4}[\/-]\d{1,2}[\/-]\d{1,2}$/', $var);
    }

    protected static function dateDE( $var )
    {
        return preg_match('/^\d\d?\.\d\d?\.\d\d\d?\d?$/', $var);
    }

    protected static function number( $var )
    {
        return preg_match('/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/', strval($var));
    }

    protected static function numberDE( $var )
    {
        return preg_match('/^-?(?:\d+|\d{1,3}(?:\.\d{3})+)(?:,\d+)?$/', $var);
    }

    protected static function digits( $var )
    {
        return preg_match('/^\d+$/', $var);
    }

    protected static function equalTo( $var, $param )
    {
        return ( $var == $param );
    }
}