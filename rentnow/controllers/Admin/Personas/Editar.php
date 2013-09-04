<?php

class Admin_Personas_Editar extends Controller
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

    public function get()
    {
        return array(
            'usuario' => $this->usuario,
            'persona' => $this->persona,

            'nombre' => Request::getPost('nombre', $this->persona->found() ? $this->persona->nombre : '')
        );
    }

    public function post()
    {
        $this->persona->nombre = Request::getPost('nombre', '');
        $this->persona->update();

        return "/admin/personas/$this->usuario?edited=1";
    }
}