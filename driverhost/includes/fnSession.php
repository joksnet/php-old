<?php

function dhIsLogin()
{
    return ( isset($_SESSION['dhUID']) );
}

function dhLogout()
{
    unset($_SESSION['dhUID']);
}

function dhLogin( $uid )
{
    $_SESSION['dhUID'] = $uid;
}

function dhUID()
{
    if ( dhIsLogin() )
        return $_SESSION['dhUID'];
    else
        return false;
}