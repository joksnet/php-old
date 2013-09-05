<?php

$root = realpath( dirname( __FILE__ ) );

include_once "$root/config.php";
include_once "$root/common.php";

if ( dhHasGet('name') && !( dhIsLogin() ) )
    dhTheme('register-success');
else
    dhRedirect('index');