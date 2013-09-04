<?php

class Inmueble_Pagina_Fotos extends Controller
{
    protected $inmueble;
    protected $pagina;

    protected $datos;
    protected $fotos;

    public function init( $inmueble, $pagina )
    {
        $this->inmueble = $inmueble;
        $this->pagina = $pagina;

        if ( !( $this->inmueble->found() ) )
            return false;

        if ( !( $this->pagina->found() ) )
            return false;

        $this->datos = Inmuebles_Paginas_Datos::allPairs(array(
            'inmueble_pagina_id' => $this->pagina->inmueble_pagina_id
        ));

        $this->fotos = Inmuebles_Fotos::all(array(
            'inmueble_id' => $this->inmueble->inmueble_id
        ), array( 'posicion' ));

        return true;
    }

    public function get()
    {
        return array(
            'inmueble'  => $this->inmueble,
            'pagina'    => $this->pagina,

            'datos'     => $this->datos,
            'fotos'     => $this->fotos
        );
    }
}