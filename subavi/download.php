<?php

$root = realpath( dirname( __FILE__ ) );

include_once "$root/config.php";
include_once "$root/common.php";

$data = Db::query(
    "SELECT s.id
          , s.filename
          , s.ext
     FROM subs s
     WHERE s.id = '" . Request::getQuery('id') . "'
     LIMIT 1"
);

if ( $data )
{
    Db::query(
        "UPDATE subs
         SET downloads = downloads + 1
         WHERE id = '{$data[0]['id']}'"
    );

    $fullpath = "$root/upload/{$data[0]['id']}";
    # $fullpath = "/var/www/subs/{$data[0]['id']}";

    header('Cache-Control: public, must-revalidate');
    header('Pragma: hack');
    header('Content-Type: application/octet-stream');
    header('Content-Length: ' . strval( filesize($fullpath) ) );
    header('Content-Disposition: attachment; filename="' . $data[0]['filename'] . '.' . $data[0]['ext'] . '"');
    header('Content-Transfer-Encoding: binary');

    readfile($fullpath);
}
else
{
    Theme::_('NotFound');
}