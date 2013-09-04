<?php

class Admin_Personas_Editar_Contrasena extends Controller
{
    protected $usuario;
    protected $persona;

    public function init( $usuario )
    {
        if ( !( Session::getInstance()->usuario ) )
            return '/admin/ingresar';

        $this->usuario = $usuario;

        $this->persona = new Personas();
        $this->persona->usuario = $usuario;
        $this->persona->queryAll();

        return true;
    }

    public function validation()
    {
        return array(
            'contrasena' => 'required[4:30]'
        );
    }

    public function get()
    {
        return array(
            'usuario' => $this->usuario,
            'persona' => $this->persona
        );
    }

    public function post()
    {
        $this->persona->contrasena = md5(
            Request::getPost('contrasena', '')
        );

        $this->persona->update();

        return "/admin/personas/$this->usuario?edited=1";
    }
}