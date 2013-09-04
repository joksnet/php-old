<?php

class Inmueble_Pagina_HTML extends Controller
{
    protected $inmueble;
    protected $pagina;

    protected $html = '';

    public function init( $inmueble, $pagina )
    {
        $this->inmueble = $inmueble;
        $this->pagina = $pagina;

        if ( !( $this->inmueble->found() ) )
            return false;

        if ( !( $this->pagina->found() ) )
            return false;

        $datos = Inmuebles_Paginas_Contenidos_Datos::allPairs(array(
            'inmueble_pagina_contenido_id' => $this->pagina->id
        ));

        if ( isset($datos['html']) )
            $this->html = $datos['html']->contenido;

        return true;
    }

    public function get()
    {
        return array(
            'inmueble'  => $this->inmueble,
            'pagina'    => $this->pagina,

            'html'      => $this->html
        );
    }
}