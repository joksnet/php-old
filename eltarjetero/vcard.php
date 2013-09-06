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
          , tarjetas.email
          , tarjetas.direccion1
          , tarjetas.direccion2
          , tarjetas.ciudad
          , tarjetas.estado
          , tarjetas.pais
          , tarjetas.telefono
          , tarjetas.fax
          , tarjetas.web
     FROM tarjetas
     WHERE tarjetas.id = '$id'
     LIMIT 1"
);

if ( $tarjetas )
{
    $tarjeta = $tarjetas[0];

    Db::query(
        "UPDATE tarjetas
         SET descargas = descargas + 1
         WHERE tarjetas.id = '$id'"
    );

    $vcardName = strtolower( str_replace( ' ', '_', $tarjeta['nombre'] ) );
    $vcard = "BEGIN:VCARD
VERSION:2.1
N:;{$tarjeta['nombre']}
FN:{$tarjeta['nombre']}
ORG:{$tarjeta['empresa']}
TITLE:{$tarjeta['cargo']}
TEL;WORK;VOICE:{$tarjeta['telefono']}
TEL;WORK;FAX:{$tarjeta['fax']}
ADR;WORK;ENCODING=QUOTED-PRINTABLE:;;{$tarjeta['direccion1']}=0D=0A{$tarjeta['direccion2']};{$tarjeta['ciudad']};{$tarjeta['estado']};;{$tarjeta['pais']}
LABEL;WORK;ENCODING=QUOTED-PRINTABLE:{$tarjeta['direccion1']}=0D=0A{$tarjeta['direccion2']}=0D=0A{$tarjeta['ciudad']}, {$tarjeta['estado']}=0D=0A{$tarjeta['pais']}
URL;WORK:{$tarjeta['web']}
EMAIL;PREF;INTERNET:{$tarjeta['email']}
REV:20080609T134139Z
END:VCARD";

    header('Cache-Control: public, must-revalidate');
    header('Pragma: hack');
    header('Content-Type: application/octet-stream');
    header('Content-Length: ' . strlen( $vcard ) );
    header('Content-Disposition: attachment; filename="' . $vcardName . '.vcf"');
    header('Content-Transfer-Encoding: binary');

    echo $vcard;
}
else
{
    die('a');
}