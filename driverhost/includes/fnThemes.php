<?php

function dhTheme( $theme )
{
    global $root;

    static $current = '';

    if ( empty($current) )
        $current = dhConfig('siteTheme');

    require_once "$root/themes/$current/$theme.php";
}