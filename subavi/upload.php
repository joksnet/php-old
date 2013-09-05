<?php

$root = realpath( dirname( __FILE__ ) );

include_once "$root/config.php";
include_once "$root/common.php";

function upload()
{
    global $root;

    if ( $_FILES['sub']['size'] == 0 && $_FILES['sub']['error'] > 0 )
        return 'Error al subir el subtítulo.';

    $file    = $_FILES['sub']['name'];
    $frame   = Request::getPost('frame');
    $comment = Request::getPost('comment');
    $credits = Request::getPost('credits');

    if ( empty($frame) )
        return 'Falta el frame.';

    $format     = substr($file, -3);
    $formatName = Formats::get($format);

    if ( empty($formatName) )
        return 'El archivo no es de un formato válido.';

    $filename = substr($file, 0, strlen($file) - 4);
    $id = Db::insert('subs', array(
        'filename' => $filename,
        'ext'      => $format,
        'frame'    => $frame,
        'comment'  => $comment,
        'credits'  => $credits,
        'time'     => time()
    ));

    $fullpath = "$root/upload/$id";
    # $fullpath = "/var/www/subs/$id";

    if ( !( @move_uploaded_file($_FILES['sub']['tmp_name'], $fullpath) ) )
    {
        if ( $id )
            Db::delete('subs', "id = '$id'");

        return 'No se logró subir el archivo, intente nuevamente en unos minutos.';
    }

    header("Location: /$id.html");
}

$msg = '';

if ( Request::isPost() )
    $msg = upload();

Theme::_('Upload', array( 'msg' => $msg ));