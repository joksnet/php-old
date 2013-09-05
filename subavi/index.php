<?php

$root = realpath( dirname( __FILE__ ) );

include_once "$root/config.php";
include_once "$root/common.php";

/**
if ( isset($_GET['letter']) )
    Theme::_('Letter');
else
 */
    Theme::_('FrontPage');