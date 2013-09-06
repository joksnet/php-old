<?php

/**
 * Devuelve el archivo pasado con la ruta relativa
 * del template activo.
 *
 * @param string $fileName
 * @return string
 */
function pathTpl( $fileName = null )
{
    global $conf;
    return 'lib/tpl/' . $conf['tpl'] . '/'
         . ( ( is_null($fileName) ) ? '' : $fileName );
}
