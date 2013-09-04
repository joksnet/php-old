<?php

class Admin_Inmuebles_Paginas_Editar_Fotos extends Controller
{
    protected $inmuebleCodigo;
    protected $inmueble;

    protected $paginaCodigo;
    protected $pagina;

    protected $datos;
    protected $datosNombres = array();

    public function init( $inmueble, $pagina )
    {
        if ( !( Session::getInstance()->usuario ) )
            return '/admin/ingresar';

        $this->inmuebleCodigo = $inmueble;
        $this->inmueble = new Inmuebles();
        $this->inmueble->codigo = $inmueble;
        $this->inmueble->queryAll();

        if ( !( $this->inmueble->found() ) )
            return true;

        $this->paginaCodigo = $pagina;
        $this->pagina = new Inmuebles_Paginas();
        $this->pagina->inmueble_id = $this->inmueble->id;
        $this->pagina->codigo = $pagina;
        $this->pagina->queryAll();

        if ( !( $this->pagina->found() ) )
            return true;

        if ( 'fotos' !== $this->pagina->tipo )
            return "/admin/inmuebles/$inmueble/paginas/$pagina/editar";

        $this->datos = Inmuebles_Paginas_Datos::allPairs(array(
            'inmueble_pagina_id' => $this->pagina->id
        ));

        return true;
    }

    public function get()
    {
        $return = array(
            'inmuebleCodigo' => $this->inmuebleCodigo,
            'inmueble'       => $this->inmueble,

            'paginaCodigo'   => $this->paginaCodigo,
            'pagina'         => $this->pagina
        );

        foreach ( $this->datosNombres as $nombre )
        {
            $return[$nombre] = Request::getPost($nombre,
                isset($this->datos[$nombre])
                    ? $this->datos[$nombre]->contenido : ''
            );
        }

        return $return;
    }

    public function post()
    {
        foreach ( $this->datosNombres as $nombre )
        {
            $insert = ( !( isset($this->datos[$nombre]) ) );

            $valor = Request::getPost($nombre,
                $insert ? '' : $this->datos[$nombre]->contenido
            );

            if ( $insert && empty($valor) )
                continue;

            if ( $insert )
            {
                $this->datos[$nombre] = new Inmuebles_Paginas_Datos();
                $this->datos[$nombre]->inmueble_pagina_id = $this->pagina->id;
                $this->datos[$nombre]->nombre = $nombre;
            }

            $this->datos[$nombre]->contenido = $valor;

            if ( $insert )
                $this->datos[$nombre]->insert();
            else
                $this->datos[$nombre]->update();
        }

        return "/admin/inmuebles/$this->inmuebleCodigo/paginas/$this->paginaCodigo?fotos=1";
    }
}