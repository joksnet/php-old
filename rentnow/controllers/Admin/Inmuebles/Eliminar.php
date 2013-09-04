<?php

class Admin_Inmuebles_Eliminar extends Controller
{
    protected $codigo;
    protected $inmueble;

    public function init( $codigo )
    {
        if ( !( Session::getInstance()->usuario ) )
            return '/admin/ingresar';

        $this->codigo = $codigo;

        $this->inmueble = new Inmuebles();
        $this->inmueble->codigo = $codigo;
        $this->inmueble->queryAll();

        return true;
    }

    public function get()
    {
        return array(
            'codigo'   => $this->codigo,
            'inmueble' => $this->inmueble
        );
    }

    public function post()
    {
        Inmuebles::destroy(
            $this->inmueble
        );

        return "/admin/inmuebles?deleted=$this->codigo";
    }
}