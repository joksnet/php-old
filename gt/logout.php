<?php

$root = realpath( dirname( __FILE__ ) );

include_once "$root/includes/functions.php";
include_once "$root/includes/common.php";

gtLogout();
gtRedirect('login');