<?php

$root = realpath( dirname( __FILE__ ) );

include_once "$root/config.php";
include_once "$root/common.php";

if ( !( dhIsLogin() ) )
    dhRedirect('index');

if ( dhHasGet('n') )
{
    $id = dhGet('n');

    $networks = dhQuery(
        "SELECT n.id_networks
              , n.netname
         FROM networks n
         WHERE n.id_networks = '$id'"
    );

    if ( $networks )
    {
        $network = array_shift($networks);
        $netname = $network['netname'];

        dhQueryDelete('networks', "id_networks = '$id'");

        if ( dhConfig('useModRewrite') )
            dhRedirect('networks');
        else
            dhRedirect('networks', array( 'delete' => $netname ));

        exit();
    }
}

dhRedirect('networks');