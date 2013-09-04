<?php

class Admin_Ingresar extends Controller
{
    public function get()
    {
        $configuracion = Configuration::getInstance();

        return array(
            'error'   => Request::isPost(),
            'usuario' => Request::getPost('usuario', ''),

            'nombre'  => $configuracion->nombre
        );
    }

    public function post()
    {
        $login = new Personas();
        $login->usuario = Request::getPost('usuario', '');
        $login->contrasena = md5(Request::getPost('contrasena', ''));

        if ( !( $login->queryAll() ) )
            return false;

        Session::getInstance()
            ->usuario = $login->usuario;

        return '/admin';
    }
}