<?php

$root = '.';

include_once "$root/config.php";
include_once "$root/common.php";

if ( !( isset($_GET['pid']) ) )
    die('Missing parameter');

$pid = (int) $_GET['pid'];

if ( !( Session::$logged ) )
    header("Location: {$config['root']}signin/?next={$config['root']}dd/projects/$pid/delete/");

$title = __('ProjectsDeleteTitle');

$project = Db::fetchRow(
    "SELECT name, description, uid
     FROM projects
     WHERE pid = '$pid'"
);

if ( $project )
{
    $name = $project['name'];
    $description = $project['description'];

    if ( $project['uid'] == Session::_('uid') )
        $access = true;
}

if ( !( empty($_POST) ) )
{
    if ( $pid )
    {
        Db::query(
            "DELETE FROM projects
             WHERE pid = '$pid'"
        );
    }

    header("Location: {$config['root']}dd/projects/");
}

require_once "$root/themes/$theme/ddprojectsdelete.php";