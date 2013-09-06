<?php

/**
 * Obtiene el contenido de un archivo CSS que este dentro
 * de la carpeta del template activo o del directorio publico.
 *
 * @param string $fileName
 * @return string
 */
function importCss( $fileName )
{
    settype($fileName, 'string');

    $fileTpl = pathTpl($fileName);
    $filePub = 'pub/css/' . $fileName;

    if ( @file_exists($fileTpl) )
        return fileGetContents($fileTpl);
    elseif ( @file_exists($filePub) )
        return fileGetContents($filePub);
    else
        return '';
}

/**
 * Devuelve el contenido del archivo JavaScript y si no se
 * le pasa un nombre de archivo, devuelve los archivos basicos.
 *
 * @param string $fileName
 * @return string
 */
function importJS( $fileName = null )
{
    if ( is_null($fileName) )
    {
        $basicFiles = array(
            'jquery.js'
        );

        $js = '';

        foreach ( $basicFiles as $fileName )
            $js .= importJS($fileName);

        return $js;
    }

    $fileTpl = pathTpl($fileName);
    $filePub = 'pub/js/' . $fileName;

    if ( @file_exists($fileTpl) )
        return fileGetContents($fileTpl);
    elseif ( @file_exists($filePub) )
        return fileGetContents($filePub);
    else
        return '';
}

/**
 * Crea un tag de script para incluir el archivo pasado
 * como parametro.
 *
 * @param string $fileName
 * @return string
 */
function includeJS( $fileName )
{
    settype($fileName, 'string');

    return '<script type="text/javascript"'
         . ' src="pub/js/' . $fileName . '"></script>'
         . "\n";
}

/**
 * Crea un lista con los mejores codigos, recibe un desde y
 * cantidad para paginar.
 *
 * @param int $from
 * @param int $n
 * @return string
 */
function xhtmlCodesBests( $from = 0, $n = 5 )
{
    return xhtmlOl( codesBests($from, $n) );
}

/**
 * Crea un lista en XHTML
 *
 * @param array $list
 * @param string $type
 * @return string
 */
function xhtmlList( $list, $type = 'ul' )
{
    settype($list, 'array');

    $li = '';

    foreach ( $list as $key => $value )
    {
        $li .= sprintf('<li%s>%s</li>',
            ( ( is_numeric($key) ) ? '' : sprintf(' id="%s"', $key) ),
            ( ( is_array($value) && sizeof($value) > 0 ) ? array_shift($value) . '<span>' . implode('</span><span>', $value) . '</span>' : trim(strval($value)) )
        );
    }

    return sprintf('<%s>%s</%s>', $type, $li, $type);
}

/**
 * Crea un lista numerada
 *
 * @param array $list
 * @return string
 */
function xhtmlOl( $list )
{
    return xhtmlList($list, 'ol');
}

/**
 * Crea un lista no numerada
 *
 * @param array $list
 * @return string
 */
function xhtmlUl( $list )
{
    return xhtmlList($list, 'ul');
}