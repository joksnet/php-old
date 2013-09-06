<?php

$root = realpath( dirname( __FILE__ ) );

include_once "$root/config.php";
include_once "$root/common.php";

$id = ( isset($_GET['t']) ) ? intval( $_GET['t'] ) : 0;

$tarjetas = Db::query(
    "SELECT tarjetas.id
          , tarjetas.nombre
          , tarjetas.cargo
          , tarjetas.empresa
          , MD5(tarjetas.email) AS avatar
          , tarjetas.direccion1
          , tarjetas.direccion2
          , tarjetas.ciudad
          , tarjetas.estado
          , tarjetas.pais
          , tarjetas.telefono
          , tarjetas.fax
          , tarjetas.web
          , estilos.id AS estilo
          , estilos.clase
          , COUNT(abusos.id) AS abusos
          , tarjetas.vistas
          , tarjetas.listadas
          , tarjetas.descargas
     FROM tarjetas
     LEFT JOIN estilos ON estilos.id = tarjetas.estilo AND estilos.activo = 1
     LEFT JOIN abusos ON abusos.tarjeta = tarjetas.id
     WHERE tarjetas.id = '$id'
     GROUP BY tarjetas.id
     LIMIT 1"
);

if ( $tarjetas )
{
    Db::query(
        "UPDATE tarjetas
         SET vistas = vistas + 1
         WHERE id = '$id'"
    );

    Theme::_('Tarjeta', array(
        'title'   => "{$tarjetas[0]['nombre']} « {$tarjetas[0]['empresa']}",
        'titleH1' => "{$tarjetas[0]['nombre']}",
        'tarjeta' => $tarjetas[0],
        'clase'   => ( $tarjetas[0]['clase'] ) ? $tarjetas[0]['clase'] : 'default'
    ));
}
else
{
    Theme::_('Tarjeta', array(
        'title'   => "Contacto Inexistente « " . Config::get('siteTagline'),
        'tarjeta' => array()
    ));
}