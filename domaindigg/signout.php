<?php

$root = '.';

include_once "$root/config.php";
include_once "$root/common.php";

if ( Session::$logged )
    $email = Session::_('email');

Session::destroy();

if ( $email )
    header("Location: {$config['root']}signin/?email=$email");
else
    header("Location: {$config['root']}signin/");