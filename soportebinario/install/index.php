<?php

$root = realpath( dirname( __FILE__ ) );
$root = "$root/..";

if ( !( is_readable("$root/config.php") ) )
    die('<pre><strong>Error</strong>: Falta config.php</pre>');

include_once "$root/config.php";
include_once "$root/common.php";

Theme::_('install');