<?php

class Admin_Inmuebles_Activo extends Controller
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

        if ( !( $this->inmueble->found() ) )
            return true;

        return true;
    }

    public function get()
    {
        return array(
            'codigo'   => $this->codigo,
            'inmueble' => $this->inmueble,
        );
    }

    public function post()
    {
        $this->inmueble->activo = $this->inmueble->activo == 1 ? 0 : 1;
        $this->inmueble->update();

        return "/admin/inmuebles?activo-{$this->inmueble->activo}=$this->codigo";
    }
}