<?php

$root = realpath( dirname( __FILE__ ) );

include_once "$root/config.php";
include_once "$root/common.php";

if ( !( dhIsLogin() ) )
    dhRedirect('login');

dhTheme('index');