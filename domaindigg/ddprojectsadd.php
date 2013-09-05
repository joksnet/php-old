<?php

$root = '.';

include_once "$root/config.php";
include_once "$root/common.php";

if ( !( Session::$logged ) )
    header("Location: {$config['root']}signin/?next={$config['root']}dd/projects/add/");

$title = __('ProjectsAddTitle');

if ( !( empty($_POST) ) )
{
    $name = $_POST['name'];
    $description = $_POST['description'];

    if ( strlen($name) < 3 )
        $error['name'] = true;

    if ( !( empty($description) ) && strlen($description) > 140 )
        $error['description'] = true;

    if ( empty($error) )
    {
        Db::insert('projects', array(
            'uid'         => Session::_('uid'),
            'name'        => $name,
            'description' => $description,
            'public'      => 0
        ));

        header("Location: {$config['root']}dd/projects/");
    }
}

require_once "$root/themes/$theme/ddprojectsadd.php";