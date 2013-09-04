<?php

class Admin_Personas_Ver extends Controller
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
            'persona' => $this->persona
        );
    }
}