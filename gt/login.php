<?php

$root = realpath( dirname( __FILE__ ) );

include_once "$root/includes/functions.php";
include_once "$root/includes/common.php";

if ( isset($_POST['username']) && isset($_POST['password']) )
{
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['password'] = $_POST['password'];

    if ( $mbox = gtLogin() )
    {
        @imap_close($mbox);
        gtRedirect('index');
    }

    gtLogout();
}

include_once "$root/includes/theme.php";