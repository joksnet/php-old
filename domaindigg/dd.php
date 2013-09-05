<?php

$root = '.';

include_once "$root/config.php";
include_once "$root/common.php";

if ( !( Session::$logged ) )
    header("Location: {$config['root']}signin/?next={$config['root']}dd/");

$projects = Db::fetchAssoc(
    "SELECT pid, name, 1 AS own
     FROM projects
     WHERE uid = '" . Session::_('uid') . "'

     UNION

     SELECT p.pid, p.name, 0 AS own
     FROM projects p
     INNER JOIN projects_access a
     ON a.pid = p.pid AND a.uid = '" . Session::_('uid') . "'

     ORDER BY name
     LIMIT 10"
);

if ( isset($_GET['noticed']) )
{
    Db::query(
        "UPDATE projects_access
         SET notice = 0
         WHERE uid = '" . Session::_('uid') . "'"
    );
}
else
{
    $notices = Db::fetchAssoc(
        "SELECT a.aid, p.pid, p.name, a.message
         FROM projects_access a
         INNER JOIN projects p ON p.pid = a.pid
         WHERE a.uid = '" . Session::_('uid') . "'
           AND a.notice = 1"
    );
}

require_once "$root/themes/$theme/dd.php";