<?php

function dhIsPost()
{
    return ( sizeof($_POST) > 0 );
}

function dhPost( $var, $default = null )
{
    return ( isset($_POST[$var]) ) ? $_POST[$var] : $default;
}

function dhHasPost( $var )
{
    return ( isset($_POST[$var]) );
}

function dhGet( $var, $default = null )
{
    return ( isset($_GET[$var]) ) ? $_GET[$var] : $default;
}

function dhHasGet( $var )
{
    return ( isset($_GET[$var]) );
}

function dhGlobal( $varName )
{
    global $$varName;
    return $$varName;
}

$validation = array();

function dhValid( $argv = null )
{
    global $validation;

    if ( is_array($argv) )
    {
        $isValid = true;

        foreach ( $argv as $varName => $rule )
        {
            $var = dhPost($varName);

            if ( is_string($rule) )
                $rule = array( $rule => true );

            foreach ( $rule as $ruleName => $param )
            {
                $fnName = "dhValid" . ucfirst( strtolower( $ruleName ) );
                $fn = $fnName($var, $param);

                if ( !( $fn ) )
                    $isValid = false;

                if ( sizeof($rule) == 1 )
                    $validation[$varName] = $fn;
                else
                    $validation[$varName][$ruleName] = $fn;
            }
        }

        return $isValid;
    }
    elseif ( is_string($argv) )
    {
        if ( strpos($argv, '.') !== false )
            list($varName, $ruleName) = explode('.', $argv);
        else
        {
            $varName = $argv;
            $ruleName = null;
        }

        if ( isset($validation[$varName]) )
        {
            if ( is_array($validation[$varName]) )
            {
                if ( null === $ruleName )
                {
                    foreach ( $validation[$varName] as $bool )
                        if ( !( $bool ) )
                            return false;

                    return true;
                }
                elseif ( isset($validation[$varName][$ruleName]) )
                    return $validation[$varName][$ruleName];
            }
            else
                return $validation[$varName];
        }

        return true;
    }
    elseif ( null === $argv )
    {
        foreach ( $validation as $bool )
        {
            if ( is_array($bool) )
            {
                foreach ( $bool as $bool2 )
                    if ( !( $bool2 ) )
                        return false;
            }
            elseif ( !( $bool ) )
                return false;
        }

        return true;
    }
}

function dhNValid( $argv = null )
{
    return ( !( dhValid($argv) ) );
}

function dhValidRequired( $var )
{
    return ( !( empty($var) ) );
}

function dhValidEqual( $var, $param )
{
    return ( $var == dhPost($param) );
}