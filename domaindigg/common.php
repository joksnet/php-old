<?php

@error_reporting(E_ALL ^ E_NOTICE);
@set_magic_quotes_runtime(0);

if ( empty($root) )
    $root = '.';

/**
 * @ignore
 */
$theme = 'default';

require_once "$root/includes/String.php";
require_once "$root/includes/Lang.php";
require_once "$root/includes/Session.php";
require_once "$root/includes/Db.php";

Db::connect($configdb);

$config = Db::fetchPairs(
    "SELECT name, value
     FROM config"
);

if ( isset($config['lang']) )
    Lang::current( $config['lang'] );

Session::start();