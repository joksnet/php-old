<?php

$root = realpath( dirname( __FILE__ ) );

include_once "$root/config.php";
include_once "$root/common.php";

$query = trim( Request::getPost('q') );

if ( !( empty($query) ) && strlen( $query ) > 2 && Request::isPost() )
{
    $dataQuery = strtolower( $query );
    $dataQuery = str_replace(' ', '%', $dataQuery);

    $data = Db::query(
        "SELECT s.id
         FROM subs s
         WHERE LCASE(s.filename) LIKE '%$dataQuery%'
         ORDER BY s.downloads DESC"
    );

    $ids = array();

    foreach ( (array) $data as $row )
        $ids[] = $row['id'];

    if ( empty($data) || empty($ids) )
    {
        Theme::_('NotFound'); exit();
    }

    $search = Db::query(
        "SELECT s.id
         FROM search s
         WHERE LCASE(s.query) = '" . strtolower( $query ) . "'"
    );

    if ( $search )
    {
        $id = $search[0]['id'];

        Db::query(
            "UPDATE search
             SET ids = '" . implode( ',', $ids ) . "'
             WHERE id = '$id'"
        );
    }
    else
    {
        $id = Db::insert('search', array(
            'query' => $query,
            'cant'  => 0,
            'ids'   => implode( ',', $ids )
        ));
    }

    header("Location: /q/$id.html");
}
else
{
    Theme::_('NotFound');
}