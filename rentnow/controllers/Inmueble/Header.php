<?php

class Inmueble_Header extends Controller
{
    protected $inmueble;
    protected $pagina;

    protected $title;
    protected $paginas;
    protected $sidebar;

    public function init( $inmueble, $pagina, $title = null )
    {
        $this->inmueble = $inmueble;
        $this->pagina = $pagina;

        if ( !( $this->inmueble->found() ) )
            return false;

        if ( !( $this->pagina->found() ) )
            return false;

        if ( $this->inmueble->inmueble_pagina_id_lateral > 0 )
        {
            $this->sidebar = Inmuebles_Paginas_Contenidos::onePagina(
                $this->inmueble->inmueble_pagina_id_lateral
            );
        }

        $this->paginas = Inmuebles_Paginas_Contenidos::allPagina(
            array('inmueble_id' => $this->inmueble->inmueble_id, 'menu' => 1, 'idioma' => Translate::locale()),
            array('posicion')
        );

        if ( is_array($title) )
            $this->title = implode(' &raquo; ', array_reverse($title));
        elseif ( is_string($title) )
            $this->title = $title;

        return true;
    }

    public function get()
    {
        $configuracion = Configuration::getInstance();

        return array(
            'inmueble' => $this->inmueble,
            'pagina'   => $this->pagina,

            'title'  => $this->title,
            'nombre' => $configuracion->nombre,

            'paginas'      => $this->paginas,
            'paginasCount' => sizeof($this->paginas),

            'sidebar' => $this->sidebar
        );
    }
}