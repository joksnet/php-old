<?php

$root = realpath( dirname( __FILE__ ) );

include_once "$root/config.php";
include_once "$root/common.php";

if ( dhIsLogin() )
    dhRedirect('index');

if ( dhIsPost() )
{
    $name = dhPost('name');

    if ( dhValid( array( 'name' => 'required' ) ) )
    {
        die('die');
    }
}

dhTheme('lostpassword');