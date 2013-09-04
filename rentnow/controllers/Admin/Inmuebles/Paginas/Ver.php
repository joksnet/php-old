<?php

class Admin_Inmuebles_Paginas_Ver extends Controller
{
    protected $inmuebleCodigo;
    protected $inmueble;

    protected $paginaCodigo;
    protected $pagina;

    protected $contenidos;
    protected $datos;

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

        $this->contenidos = Inmuebles_Paginas_Contenidos::allContenidos(array(
            'inmueble_pagina_id' => $this->pagina->id
        ));

        $this->datos = Inmuebles_Paginas_Datos::allPairs(array(
            'inmueble_pagina_id' => $this->pagina->id
        ));

        return true;
    }

    public function get()
    {
        return array(
            'inmuebleCodigo' => $this->inmuebleCodigo,
            'inmueble'       => $this->inmueble,

            'paginaCodigo'   => $this->paginaCodigo,
            'pagina'         => $this->pagina,

            'contenidos'     => $this->contenidos,
            'datos'          => $this->datos,

            'idiomas'        => Translate::all()
        );
    }
}