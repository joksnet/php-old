<?php

$urls = array(
    'hello/(.*)/'   => 'HelloWorld',
    'hello/'        => 'HelloWorld',

    'api/(.*)/'     => 'Api',

    '\+/(.*)/'      => 'Css',
    '\+/'           => 'Css',

    '@@/(.*)/'      => 'Icon',

    '\*/(.*)/'      => 'JavaScript',
    '\*/'           => 'JavaScript',

    '(nocookies)/'  => 'Html',
    '(nobrowser)/'  => 'Html',
    '(i3t)/'        => 'Html',
    '(login)/'      => 'Html',
    ''              => 'Html'
);

include_once 'inc/path.php';

Path::$path = realpath( dirname( __FILE__ ) );

include_once Path::real('config.php');
include_once Path::real('inc/common.php');

Web::dispatch($urls);