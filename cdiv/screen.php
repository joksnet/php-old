<?php

$root = realpath( dirname( __FILE__ ) );

include_once "$root/config.php";
include_once "$root/common.php";

$id = $_GET['i'];

if ( $id == 'rand' )
{
    define('RAND', true);

    $sql = "SELECT id
            FROM errors
            ORDER BY RAND()
            LIMIT 1";

    if ( !( $result = @mysql_query($sql) ) )
        die( mysql_error() );

    $row = @mysql_fetch_assoc($result);
    $id  = $row['id'];
}

$sql = "SELECT id, name, url
        FROM errors
        WHERE errors.id = '$id'";

if ( !( $result = @mysql_query($sql) ) )
    die( mysql_error() );

if ( $row = @mysql_fetch_assoc($result) )
{
    $name = $row['name'];
    $url = $row['url'];
}

require_once "$root/theme/screen.php";