<?php

$root = '.';

include_once "$root/config.php";
include_once "$root/common.php";

if ( !( isset($_GET['pid']) ) )
    die('Missing parameter');

$pid = (int) $_GET['pid'];

if ( !( Session::$logged ) )
    header("Location: {$config['root']}signin/?next={$config['root']}dd/projects/$pid/edit/");

$title = __('ProjectsEditTitle');

$project = Db::fetchRow(
    "SELECT name, description, uid
     FROM projects
     WHERE pid = '$pid'"
);

if ( $project )
{
    $name = $project['name'];
    $description = $project['description'];
    $public = ( $project['public'] ) ? 1 : 0;

    $tlds = Db::fetchAssoc(
        "SELECT t.tid, t.domain, t.description, r.pid
         FROM tlds t
         LEFT JOIN projects_tlds r ON r.tid = t.tid AND r.pid = '$pid'
         WHERE t.suggest = 0
         ORDER BY t.domain"
    );

    if ( $project['uid'] == Session::_('uid') )
        $access = true;
}

if ( !( empty($_POST) ) )
{
    $name = $_POST['name'];
    $description = $_POST['description'];
    $public = ( $_POST['public'] ) ? 1 : 0;
    $domains = $_POST['domain'];

    if ( strlen($name) < 3 )
        $error['name'] = true;

    if ( !( empty($description) ) && strlen($description) > 140 )
        $error['description'] = true;

    if ( empty($error) )
    {
        if ( $pid )
        {
            if ( !( empty($domains) ) && is_array($domains) )
            {
                foreach ( $domains as $tid => $active )
                {
                    if ( !( $active ) )
                        continue;

                    $tldexists = Db::fetchOne(
                        "SELECT tid FROM projects_tlds
                         WHERE pid = '$pid' AND tid = '$tid'"
                    );

                    if ( empty($tldexists) )
                    {
                        Db::insert('projects_tlds', array(
                            'pid' => $pid,
                            'tid' => $tid
                        ));
                    }
                }

                Db::query(
                    "DELETE FROM projects_tlds
                     WHERE pid = '$pid'
                       AND tid NOT IN ( '" . implode("', '", array_keys($domains)) . "' )"
                );
            }

            Db::update('projects', array(
                'name'        => $name,
                'description' => $description,
                'public'      => $public
            ), "pid = '$pid'");
        }

        header("Location: {$config['root']}dd/projects/");
    }
}

require_once "$root/themes/$theme/ddprojectsedit.php";