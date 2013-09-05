<?php

function dhNetworks()
{
    static $networks = null;

    if ( null === $networks )
    {
        $networks = dhQuery(
            "SELECT n.id_networks
                  , n.netname
             FROM networks n
             WHERE n.id_users = '" . dhUID() . "'"
        );
    }

    return $networks;
}