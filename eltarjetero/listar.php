<?php

$root = realpath( dirname( __FILE__ ) );

include_once "$root/config.php";
include_once "$root/common.php";

$por = ( isset($_GET['por']) ) ? $_GET['por'] : '';
$por = ( $por == 'nombre' || $por == 'empresa' ) ? $por : '';

$letra  = ( isset($_GET['letra']) ) ? strtolower( substr($_GET['letra'], 0, 1) ) : '';
$buscar = ( isset($_GET['buscar']) ) ? urldecode( $_GET['buscar'] ) : '';

$title = 'Resultados de Búsqueda de Empresas';
$order = 'empresa';
$glue  = 'OR';

$results = array();
$where   = array();

if ( $letra )
{
    $title = 'Listado de Empresas con "' . strtoupper( $letra ) . '"';
    $order = 'empresa';

    if ( $por == 'nombre' )
    {
        $title = 'Listado de Contactos con "' . strtoupper( $letra ) . '"';
        $order = 'nombre';
    }

    if ( $por == 'nombre' || empty($por) )
        $where[] = "LOWER( LEFT(tarjetas.nombre, 1) ) = '$letra'";
    if ( $por == 'empresa' || empty($por) )
        $where[] = "LOWER( LEFT(tarjetas.empresa, 1) ) = '$letra'";
}
elseif ( $buscar )
{
    $title = $buscar;

    if ( $por == 'nombre' )
        $order = 'nombre';

    if ( strpos($buscar, '@') !== false )
    {
        $parts   = explode('@', $buscar);
        $nombre  = array_shift($parts);
        $empresa = implode('@', $parts);

        $title = "$nombre de $empresa";
        $glue  = 'AND';

        $nombre  = strtolower( str_replace(' ', '%', $nombre ) );
        $empresa = strtolower( str_replace(' ', '%', $empresa ) );

        if ( $por == 'nombre' || empty($por) )
            $where[] = "LOWER( tarjetas.nombre ) LIKE '%$nombre%'";
        if ( $por == 'empresa' || empty($por) )
            $where[] = "LOWER( tarjetas.empresa ) LIKE '%$empresa%'";
    }
    else
    {
        $buscar = strtolower( str_replace(' ', '%', $buscar) );

        if ( $por == 'nombre' || empty($por) )
            $where[] = "LOWER( tarjetas.nombre ) LIKE '%$buscar%'";
        if ( $por == 'empresa' || empty($por) )
            $where[] = "LOWER( tarjetas.empresa ) LIKE '%$buscar%'";
    }
}

if ( $where )
{
    $tarjetas = Db::query(
        "SELECT tarjetas.id
              , tarjetas.nombre
              , tarjetas.empresa
         FROM tarjetas
         WHERE " . implode(" $glue ", $where) . "
         ORDER BY tarjetas.$order"
    );

    if ( $tarjetas )
    {
        Db::query(
            "UPDATE tarjetas
             SET listadas = listadas + 1
             WHERE " . implode(" $glue ", $where) . ""
        );
    }
}

Theme::_('Listar', array(
    'title'    => $title,
    'tarjetas' => $tarjetas
));