<?php

function error( $msg, $file, $line )
{
    die("$msg File $file, Line $line");
}

function menu()
{
    static $menu = array(
        'Inicio'           => 'panel.php',
        'Contactos'        => 'contactos.php',
        'Agregar Contacto' => 'contactos.php?add',
        'Salir'            => 'logout.php'
    );

    $file = substr($_SERVER['PHP_SELF'], 1);
    $fileParams = array();

    if ( sizeof($_GET) > 0 )
    {
        foreach ( $_GET as $key => $value )
        {
            if ( strlen($value) == 0 )
                $fileParams[] = "$key";
            else
                $fileParams[] = "$key=$value";
        }
    }

    if ( sizeof($fileParams) > 0 )
        $file .= '?' . implode('&', $fileParams);

    echo "<ul id=\"menu\">";

    foreach ( $menu as $text => $link )
    {
        $className = ( $file == $link ) ? ' class="active"' : '';
        echo "<li$className><a href=\"$link\">$text</a></li>";
    }

    echo "</ul>";
}

function camelCase( $str, $lowerFirst = true )
{
    # $chars = explode('', $str);
    $spaces = array(' ', '_', '-', '+');

    $camelCase = '';
    $toUpper = false;

    # foreach ( $chars as $char )
    for ( $i = 0, $l = strlen($str); $i < $l; $i++ )
    {
        $char = substr($str, $i, 1);
        $ord = ord($char);

        if ( ( $ord >= 65 && $ord <= 90 ) || ( $ord >= 97 && $ord <= 122 ) || ( $ord >= 48 && $ord <= 57 ) )
        {
            if ( $toUpper )
                $char = strtoupper($char);
            else
                $char = strtolower($char);

            $camelCase .= $char;
            $toUpper = false;
        }
        elseif ( in_array($char, $spaces) )
            $toUpper = true;
    }

    if ( $lowerFirst )
        $camelCase = strtolower( substr($camelCase, 0, 1) ) . substr($camelCase, 1);
    else
        $camelCase = strtoupper( substr($camelCase, 0, 1) ) . substr($camelCase, 1);

    return $camelCase;
}