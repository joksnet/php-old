<?php

class Admin_Personas_Agregar extends Controller
{
    protected $unique = true;

    public function init()
    {
        if ( !( Session::getInstance()->usuario ) )
            return '/admin/ingresar';

        return true;
    }

    public function validation()
    {
        return array(
            'usuario'    => 'code[2:20]',
            'contrasena' => 'required[4:30]'
        );
    }

    public function get()
    {
        return array(
            'unique'  => $this->unique,
            'usuario' => Request::getPost('usuario', ''),
            'nombre'  => Request::getPost('nombre', '')
        );
    }

    public function post()
    {
        $add = new Personas();
        $add->usuario = strtolower(
            Request::getPost('usuario', '')
        );

        if ( $add->queryAll() )
            return ( $this->unique = false ) && false;

        $add->nombre = Request::getPost('nombre', '');

        if ( $contrasena = Request::getPost('contrasena', '') )
            $add->contrasena = md5($contrasena);

        $add->inscripcion = time();
        $add->insert();

        return "/admin/personas/$add->usuario?added=1";
    }
}