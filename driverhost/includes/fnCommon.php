<?php

function dhConfig( $name )
{
    static $config = array();

    if ( sizeof($config) == 0 )
    {
        $data = dhQuery(
            "SELECT c.name, c.value
             FROM config c"
        );

        foreach ( (array) $data as $row )
            $config[$row['name']] = $row['value'];
    }

    if ( isset($config[$name]) )
        return $config[$name];
    else
        return null;
}

function dhMenu()
{
    if ( dhIsLogin() )
    {
        $menu = array();

        $netmenu = array( 'label' => dhLang('Redes'), 'url' => 'networks', 'items' => array(
            array( 'label' => dhLang('Agregar'), 'url' => 'networks', 'params' => array( 'action' => 'add' ) )
        ) );

        $networks = dhNetworks();

        foreach ( $networks as $network )
            $netmenu['items'][] = array( 'label' => $network['netname'], 'url' => 'networks', 'params' => array( 'n' => $network['id_networks'] ) );

        $menu[] = $netmenu;
        $menu[] = array( 'label' => dhLang('Perfil'), 'url' => 'profile' );

        return $menu;
    }

    return array();
}