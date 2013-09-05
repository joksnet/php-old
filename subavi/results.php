<?php

$root = realpath( dirname( __FILE__ ) );

include_once "$root/config.php";
include_once "$root/common.php";

$data = Db::query(
    "SELECT s.id
          , s.query
          , s.ids
     FROM search s
     WHERE s.id = '" . Request::getQuery('id') . "'"
);

if ( $data )
{
    $id    = $data[0]['id'];
    $ids   = $data[0]['ids'];
    $query = $data[0]['query'];

    # $query = $search['query'];
    # $query = strtolower( $query );
    # $query = str_replace(' ', '%', $query);

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
         WHERE s.id IN ( $ids )
         ORDER BY s.downloads DESC"
    );

    if ( $data )
    {
        Db::query(
            "UPDATE search
             SET time = '" . time() . "'
               , cant = cant + 1
             WHERE id = '$id'"
        );

        Theme::_('Results', array(
            'title' => $query,
            'data'  => $data
        ));

        exit();
    }
}

Theme::_('NotFound');