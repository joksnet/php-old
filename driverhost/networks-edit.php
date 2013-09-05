<?php

$root = realpath( dirname( __FILE__ ) );

include_once "$root/config.php";
include_once "$root/common.php";

if ( !( dhIsLogin() ) )
    dhRedirect('index');

if ( dhIsPost() )
{
    if ( dhValid( array( 'netname' => 'required' ) ) )
    {
        $netid   = dhPost('netid', 0);
        $netname = dhPost('netname', 'WORKGROUP');

        dhQueryUpdate('networks', array(
            'netname'  => $netname
        ), "id_networks = '" . $netid . "'");

        if ( dhConfig('useModRewrite') )
            dhRedirect('networks', array( 'n' => $netid ));
        else
            dhRedirect('networks', array( 'edit' => urlencode( $netname ) ));
    }
}

if ( dhHasGet('n') )
    $netid = dhGet('n');

$networks = dhQuery(
    "SELECT n.id_networks
          , n.netname
     FROM networks n
     WHERE n.id_networks = '$netid'
       AND n.id_users = '" . dhUID() . "'"
);

if ( $networks )
    $network = array_shift($networks);

dhTheme('networks-edit');