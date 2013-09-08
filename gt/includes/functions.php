<?php

function gtRedirect( $page )
{
    if ( !( headers_sent() ) )
        header("Location: /$page.php");

    exit();
}

function isLogin()
{
    return ( isset($_SESSION['username']) );
}

function isPage( $page )
{
    return ( strpos($_SERVER['PHP_SELF'], $page) !== false );
}

function gtLogin( $mbox = 'Inbox' )
{
    if ( $mbox == 'Inbox' )
        $mbox = 'INBOX';

    $username = $_SESSION['username'];
    $password = $_SESSION['password'];

    return @imap_open("{imap.gmail.com:993/imap/ssl}$mbox", "$username@gmail.com", "$password");
}

function gtLogout()
{
    unset($_SESSION['username']);
    unset($_SESSION['password']);

    session_destroy();
}

function rmEncodeString( $string )
{
    return $string;
}