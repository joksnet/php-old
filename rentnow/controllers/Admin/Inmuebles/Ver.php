<?php

class Admin_Inmuebles_Ver extends Controller
{
    protected $codigo;
    protected $idiomas;
    protected $inmueble;
    protected $contenidos;

    public function init( $codigo )
    {
        if ( !( Session::getInstance()->usuario ) )
            return '/admin/ingresar';

        $this->codigo = $codigo;
        $this->idiomas = Translate::all();

        $this->inmueble = new Inmuebles();
        $this->inmueble->codigo = $codigo;
        $this->inmueble->queryAll();

        if ( !( $this->inmueble->found() ) )
            return true;

        $contenidos = Inmuebles_Contenidos::all(array(
            'inmueble_id' => $this->inmueble->id
        ));

        foreach ( $contenidos as $contenido )
            $this->contenidos[$contenido->idioma] = $contenido;

        return true;
    }

    public function get()
    {
        return array(
            'codigo'     => $this->codigo,
            'idiomas'    => $this->idiomas,
            'inmueble'   => $this->inmueble,
            'contenidos' => $this->contenidos
        );
    }
}