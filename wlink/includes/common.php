<?php

include_once 'includes/config.php';
include_once 'includes/functions.php';

session_start();

$db = mysql_connect($dbConfig['hostname'], $dbConfig['username'], $dbConfig['password']);
      mysql_select_db($dbConfig['database']);

if ( strpos($_SERVER['PHP_SELF'], 'login.php') === false )
    if ( !( isset($_SESSION['UID']) ) )
        header('Location: index.php');
