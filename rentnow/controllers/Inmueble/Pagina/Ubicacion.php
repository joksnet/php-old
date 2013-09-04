<?php

class Inmueble_Pagina_Ubicacion extends Controller
{
    protected $inmueble;
    protected $pagina;

    protected $datos;
    protected $html = '';

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

        $datos = Inmuebles_Paginas_Contenidos_Datos::allPairs(array(
            'inmueble_pagina_contenido_id' => $this->pagina->id
        ));

        if ( isset($datos['html']) )
            $this->html = $datos['html']->contenido;

        return true;
    }

    public function get()
    {
        $ubicacion    = '';
        $ubicacionUrl = '';

        $width = 512;
        $height = 512;
        $zoom = 0;

        if ( isset($this->datos['ubicacion']) )
        {
            $ubicacion    = $this->datos['ubicacion']->contenido;
            $ubicacionUrl = Url::encode($ubicacion);

            $width = $this->datos['width']->contenido;
            $height = $this->datos['height']->contenido;
            $zoom = $this->datos['zoom']->contenido;
        }

        return array(
            'inmueble'  => $this->inmueble,
            'pagina'    => $this->pagina,

            'datos'     => $this->datos,
            'html'      => $this->html,

            'ubicacion'    => $ubicacion,
            'ubicacionUrl' => $ubicacionUrl,

            'width'     => $width,
            'height'    => $height,
            'zoom'      => $zoom
        );
    }
}