<?php

header('Location: http://cdiv.com.ar');
exit();

$root = realpath( dirname( __FILE__ ) );

include_once "$root/config.php";
include_once "$root/common.php";

require_once "$root/theme/front.php";