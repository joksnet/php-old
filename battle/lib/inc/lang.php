<?php

/**
 * Devuleve una cadena de texto en el idioma actual.
 *
 * @param string $string
 * @return string
 */
function lang( $string )
{
    global $lang;

    if ( isset($lang[$string]) )
        return $lang[$string];
    else
        return $string;
}