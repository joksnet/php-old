<?php

$root = realpath( dirname( __FILE__ ) );

include_once "$root/config.php";
include_once "$root/common.php";

$data = Db::query(
    "SELECT s.id
          , s.filename
          , s.ext
          , s.frame
          , s.comment
          , s.credits
          , s.downloads
          , s.time
     FROM subs s
     WHERE s.id = '" . Request::getQuery('id') . "'
     LIMIT 1"
);

if ( $data )
{
    $row = $data[0];

    Theme::_('View', array(
        'id'        => $row['id'],
        'filename'  => $row['filename'],
        'ext'       => $row['ext'],
        'frame'     => $row['frame'],
        'comment'   => $row['comment'],
        'credits'   => $row['credits'],
        'downloads' => $row['downloads'],
        'time'      => $row['time']
    ));
}
else
{
    Theme::_('NotFound');
}