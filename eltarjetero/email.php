<?php

$root = realpath( dirname( __FILE__ ) );

include_once "$root/config.php";
include_once "$root/common.php";

$id = ( isset($_GET['t']) ) ? intval( $_GET['t'] ) : 0;

$tarjetas = Db::query(
    "SELECT tarjetas.id
          , tarjetas.email
     FROM tarjetas
     WHERE tarjetas.id = '$id'
     LIMIT 1"
);

if ( $tarjetas )
{
    $email = $tarjetas[0]['email'];

    // $email = str_replace('@', ' (arroba) ', $email);
    // $email = str_replace('.', ' (punto) ', $email);
}
else
    $email = '(email no encontrado)';

$fontSize = 2;
$fontWidth = imagefontwidth($fontSize);
$fontHeight = imagefontheight($fontSize);

$image = imagecreate( ( $fontWidth * strlen($email) ), $fontHeight );
$imageBackground = imagecolorallocate($image, 255, 255, 255);
$fontColor = imagecolorallocate($image, 90, 127, 224);

header('Content-type: image/png');

imagestring($image, $fontSize, 0, 0, $email, $fontColor);
imagepng($image);
imagedestroy($image);