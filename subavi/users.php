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
     WHERE s.credits = '" . urldecode( Request::getQuery('name') ) . "'
     ORDER BY s.downloads DESC"
);

if ( $data )
{
    Theme::_('Results', array(
        'title' => $data[0]['credits'],
        'data'  => $data
    ));

    exit();
}

Theme::_('NotFound');