<?php

session_start();

mysql_connect($dbConfig['hostname'], $dbConfig['username'], $dbConfig['password']);
mysql_select_db($dbConfig['database']);

include_once "$root/includes/functions.php";
include_once "$root/includes/image.php";

if ( isset($_SESSION['CDIV_LANG']) )
    $lang = $_SESSION['CDIV_LANG'];
elseif ( isset($_GET['ln']) )
{
    $lang = $_GET['ln'];
    $_SESSION['CDIV_LANG'] = $lang;
}
else
    $lang = 'en';

if ( is_readable("$root/includes/lang/$lang.php") )
    include_once "$root/includes/lang/$lang.php";