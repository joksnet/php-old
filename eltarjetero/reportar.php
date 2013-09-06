<?php

$root = realpath( dirname( __FILE__ ) );

include_once "$root/config.php";
include_once "$root/common.php";

$id = ( isset($_GET['t']) ) ? intval( $_GET['t'] ) : 0;

$tarjetas = Db::query(
    "SELECT tarjetas.id
          , tarjetas.nombre
     FROM tarjetas
     WHERE tarjetas.id = '$id'
     LIMIT 1"
);

if ( $tarjetas )
{
    Db::insert('abusos', array(
        'tarjeta' => $id,
        'ip'      => Request::getIP(),
        'fecha'   => time()
    ));
}

Theme::_('Reportar', array(
    'id' => $id
));