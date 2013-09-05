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
        $netname = dhPost('netname', 'WORKGROUP');

        dhQueryInsert('networks', array(
            'id_users' => dhUID(),
            'netname'  => $netname,
            'created'  => time()
        ));

        if ( dhConfig('useModRewrite') )
            dhRedirect('networks');
        else
            dhRedirect('networks', array( 'success' => urlencode( $netname ) ));
    }
}

dhTheme('networks-add');