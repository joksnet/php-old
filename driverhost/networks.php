<?php

$root = realpath( dirname( __FILE__ ) );

include_once "$root/config.php";
include_once "$root/common.php";

if ( !( dhIsLogin() ) )
    dhRedirect('index');

if ( dhHasGet('action') )
{
    switch ( dhGet('action') )
    {
        case 'add':
            include_once "$root/networks-add.php";
            exit; break;

        case 'edit':
            include_once "$root/networks-edit.php";
            exit; break;

        case 'delete':
            include_once "$root/networks-delete.php";
            exit; break;
    }
}

if ( dhHasGet('n') )
{
    $networks = dhQuery(
        "SELECT n.id_networks
              , n.netname
              , n.created
              , n.modified
         FROM networks n
         WHERE n.id_networks = '" . dhGet('n') . "'"
    );

    if ( $networks )
        $network = array_shift($networks);
}

dhTheme('networks');