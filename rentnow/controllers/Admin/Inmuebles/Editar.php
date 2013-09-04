<?php

class Admin_Inmuebles_Editar extends Controller
{
    protected $codigo;
    protected $idiomas;
    protected $inmueble;

    public function init( $codigo )
    {
        if ( !( Session::getInstance()->usuario ) )
            return '/admin/ingresar';

        $this->codigo = $codigo;
        $this->idiomas = Translate::all();

        $this->inmueble = new Inmuebles();
        $this->inmueble->codigo = $codigo;
        $this->inmueble->queryAll();

        return true;
    }

    public function get()
    {
        return array(
            'codigo'   => $this->codigo,
            'idiomas'  => $this->idiomas,
            'inmueble' => $this->inmueble
        );
    }
}