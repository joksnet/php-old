<?php

$root = realpath( dirname( __FILE__ ) );

include_once "$root/config.php";
include_once "$root/common.php";

$nombre  = ( isset($_POST['nombre']) ) ? $_POST['nombre'] : '';
$empresa = ( isset($_POST['empresa']) ) ? $_POST['empresa'] : '';

if ( $nombre && $empresa )
    header('Location: /b/' . urlencode($nombre) . '@' . urlencode($empresa) . '.html');
elseif ( $nombre )
    header('Location: /b/n/' . urlencode($nombre) . '.html');
elseif ( $empresa )
    header('Location: /b/e/' . urlencode($empresa) . '.html');
else
    header('Location: /');