<?php

class Admin_Principal extends Controller
{
    public function init()
    {
        if ( !( Session::getInstance()->usuario ) )
            return '/admin/ingresar';

        return true;
    }

    public function get()
    {
        $configuracion = Configuration::getInstance();

        return array(
            'nombre' => $configuracion->nombre
        );
    }
}