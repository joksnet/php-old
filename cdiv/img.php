<?php

$root = realpath( dirname( __FILE__ ) );

include_once "$root/config.php";
include_once "$root/common.php";

$exts = array('jpg' => true, 'png' => true);
$ts = array('orig' => true, 'small' => true, 'thumb' => true);

$id = $_GET['i'];
$t = ( isset($_GET['t']) ) ? $_GET['t'] : 'orig';

if ( !( isset($ts[$t]) ) )
    $t = 'orig';

foreach ( $exts as $ext => $true )
{
    if ( is_readable("$root/upload/$t/$id.$ext") )
    {
        header("Content-Type: image/$ext");
        readfile("$root/upload/$t/$id.$ext");
        exit();
    }
}

die('Not Found');