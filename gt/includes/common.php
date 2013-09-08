<?php

session_start();

if ( !( isLogin() || isPage('login') ) )
    gtRedirect('login');

$ref = '{imap.gmail.com:993}';