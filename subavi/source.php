<?php

$root = realpath( dirname( __FILE__ ) );

include_once "$root/config.php";
include_once "$root/common.php";

$data = Db::query(
    "SELECT s.source
          , s.downloads
     FROM sources s
     WHERE s.source = '" . Request::getQuery('file') . "'
     LIMIT 1"
);

if ( $data )
{
    Db::query(
        "UPDATE sources
         SET downloads = downloads + 1
         WHERE source = '" . Request::getQuery('file') . "'"
    );

    $fullpath = "$root/sources/{$data[0]['source']}.zip";

    header('Cache-Control: public, must-revalidate');
    header('Pragma: hack');
    header('Content-Type: application/octet-stream');
    header('Content-Length: ' . strval( filesize($fullpath) ) );
    header('Content-Disposition: attachment; filename="subavi-' . $data[0]['source'] . '.zip"');
    header('Content-Transfer-Encoding: binary');

    readfile($fullpath);
}
else
{
    Theme::_('NotFound');
}