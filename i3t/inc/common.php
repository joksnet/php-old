<?php

include_once Path::real('inc/web.php');
include_once Path::real('inc/db.php');
include_once Path::real('inc/cookies.php');
include_once Path::real('inc/json.php');

Db::open($dbConfig);

$config = array();

if ( $data = Db::query('SELECT * FROM config') )
{
    $config = array_merge($config, $data);
}

ini_set('session.cookie_domain', ( strpos($_SERVER['HTTP_HOST'], '.') !== false ) ? $_SERVER['HTTP_HOST'] : '');
ini_set('session.cookie_path', '/');