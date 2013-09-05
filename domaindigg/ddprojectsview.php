<?php

$root = '.';

include_once "$root/config.php";
include_once "$root/common.php";

if ( !( isset($_GET['pid']) ) )
    die('Missing parameter');

$pid = (int) $_GET['pid'];

if ( !( Session::$logged ) )
    header("Location: {$config['root']}signin/?next={$config['root']}dd/projects/$pid/");

$project = Db::fetchRow(
    "SELECT pid, uid, name, description, public
     FROM projects
     WHERE pid = '$pid'"
);

$title = ( empty($project) ) ? __('ProjectNotFound') : $project['name'];

if ( !( empty($project) ) )
{
    $access = false;

    if ( $project['public'] )
        $access = true;
    elseif ( $project['uid'] == Session::_('uid') )
        $access = true;
    else
    {
        $usersAccess = Db::fetchPairs(
            "SELECT uid, aid
             FROM projects_access
             WHERE pid = '$pid'
               AND uid != 0"
        );

        if ( !( empty($usersAccess) ) )
            $access = ( isset($usersAccess[Session::_('uid')]) ) ? true : false;
    }

    $names = Db::fetchAssoc(
        "SELECT n.nid, n.name, n.description, u.uid, u.email
         FROM projects_names n
         INNER JOIN users u ON u.uid = n.uid
         WHERE n.pid = '$pid'"
    );
}

require_once "$root/themes/$theme/ddprojectsview.php";