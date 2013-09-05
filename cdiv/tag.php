<?php

$root = realpath( dirname( __FILE__ ) );

include_once "$root/config.php";
include_once "$root/common.php";

$tag = $_GET['t'];

if ( empty($tag) )
    header("Location: $rootURL/");

$sql = "SELECT errors.id, name
        FROM errors
        INNER JOIN errors_tags
        ON errors_tags.id = errors.id
        AND errors_tags.tag = '$tag'
        ORDER BY votes DESC";

if ( !( $result = @mysql_query($sql) ) )
    die( mysql_error() );

$errors = array();

while ( $row = @mysql_fetch_assoc($result) )
    $errors[$row['id']] = $row['name'];

require_once "$root/theme/tag.php";