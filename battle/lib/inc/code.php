<?php

include_once $realPath . 'code/abstract.php';

/**
 * Devuelve todos los codigos registrados ordenados por
 * nombre.
 *
 * @author Juan Manuel Martinez <joksnet@gmail.com>
 * @return array
 */
function codesList()
{
    $r = array();
    $d = dir($realPath . 'code/');

    while ( false !== ( $e = $d->read() ) )
        if ( $e != '.' && $e != '..' && is_dir($d->path . $e) )
            array_push($r, $e);

    $d->close(); sort($r);

    return $r;
}

/**
 * Obtiene la informacion del author del codigo, asi como
 * los puntos.
 *
 * @param string $codeName
 * @return array
 */
function codeInfo( $codeName )
{
    $fileName = 'code/' . $codeName . '/code.php';
    $fileNameInfo = 'code/' . $codeName . '/info.txt';

    if ( @file_exists($fileName) )
    {
        include_once $fileName;

        $className = 'Battle_Code_' . $codeName;

        $info = array();
        $infoInfo = array();

        if ( class_exists($className) )
        {
            $instance = new $className();
            $info = $instance->info;

            unset($instance);
        }

        if ( @file_exists($fileNameInfo) )
        {
            $infoInfo = xml2array($fileNameInfo);

            foreach ( $infoInfo['info']['children'] as $name => $value )
                $info[$name] = $value['data'];
        }

        return $info;
    }

    return array();
}

/**
 * Obtiene una lista de codigos como clave de un array y
 * la informacion de valor.
 *
 * @return array
 */
function codesListInfo()
{
    $return = array();
    $codes = codesList();

    foreach ( $codes as $code )
        $return[$code] = codeInfo($code);

    return $return;
}

/**
 * Obtiene todos los puntos en un array en donde la key
 * es el nombre del codigo y el valor la cantidad de puntos.
 * Puede ser ordenado de manera descendente o acendentemente.
 *
 * @param {asc|desc} $order
 * @return array
 */
function codesPoints( $order = 'asc' )
{
    $codes = codesListInfo();
    $points = array();

    foreach ( $codes as $code => $info )
        $points[$code] = ( isset($info['points']) ) ? $info['points'] : 0;

    arsort($points);

    if ( $order == 'desc' )
        $points = array_reverse($points);

    return $points;
}

/**
 * Devuelve un array de arrays, con con los mejores valores
 * desde $from y $n mas para poder paginar.
 *
 * @param int $from
 * @param int $n
 * @param {asc|desc} $order
 * @return array
 */
function codesBests( $from = 0, $n = 5, $order = 'asc' )
{
    settype($from, 'int');
    settype($n, 'int');

    $points = codesPoints($order);
    $result = array();
    $i = 0;

    foreach ( $points as $key => $value )
    {
        if ( $i >= $from )
            array_push($result, array($key, $value));

        if ( $i == $from + $n )
            break;

        $i++;
    }

    return $result;
}