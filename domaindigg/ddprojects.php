<?php

$root = '.';

include_once "$root/config.php";
include_once "$root/common.php";

if ( !( Session::$logged ) )
    header("Location: {$config['root']}signin/?next={$config['root']}dd/projects/");

$title = __('ProjectsTitle');

$count = Db::fetchOne("SELECT COUNT(*) FROM projects WHERE uid = '" . Session::_('uid') . "'");
$page  = ( isset($_GET['page']) ) ? $_GET['page'] : 1;
$start = ( $page - 1 ) * $config['perpage'];

$projects = Db::fetchAssoc(
    "SELECT pid, name, description
     FROM projects
     WHERE uid = '" . Session::_('uid') . "'
     ORDER BY name
     LIMIT $start,{$config['perpage']}"
);

$pagination = pagination(
    "{$config['root']}dd/projects/", $count, $config['perpage'], $page
);

require_once "$root/themes/$theme/ddprojects.php";