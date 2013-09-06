<?php

include_once "$root/includes/String.php";
include_once "$root/includes/Config.php";
include_once "$root/includes/Db.php";
include_once "$root/includes/Request.php";
include_once "$root/includes/Theme.php";

Db::open();

$letters = array();

for ( $i = ord('a'); $i <= ord('z'); $i++ )
{
    $letter = chr($i);
    $letters[$letter] = 0;
}