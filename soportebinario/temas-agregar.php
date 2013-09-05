<?php

$root = realpath( dirname( __FILE__ ) );

include_once "$root/config.php";
include_once "$root/common.php";

$id = Request::getQuery('id');

if ( is_numeric($id) )
    $where = "empresas.id = '$id'";
else
    $where = "empresas.abbr = '$id'";

$empresas = Db::query(
    "SELECT empresas.id
          , empresas.nombre
          , empresas.slogan
          , empresas.logo
          , empresas.descripcion
          , empresas.web
     FROM empresas
     WHERE $where
       AND empresas.activo = 1
     LIMIT 1"
);

if ( !( $empresas ) )
{
    Theme::_('empresas-notfound'); exit();
}

$styles = array(
    'q' => 'Pregunta',
    'i' => 'Idea',
    'p' => 'Problema'
);

$style      = substr( strtolower( Request::getQuery('style', 'q') ), 0, 1 );
$styleName  = $styles[$style];
$styleWhere = " AND productos.admite_" . strtolower($styleName) . "s = 1";

$productos = Db::query(
    "SELECT productos.id
          , productos.nombre
          , productos.logo
     FROM productos
     WHERE productos.activo = 1
     $styleWhere
     ORDER BY productos.nombre"
);

Theme::_('temas-agregar', array(
    'title'     => ( $style == 'p' ) ? "Nuevo $styleName" : "Nueva $styleName",
    'empresa'   => $empresas[0],
    'productos' => $productos
));