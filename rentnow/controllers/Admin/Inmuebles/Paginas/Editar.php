<?php

class Admin_Inmuebles_Paginas_Editar extends Controller
{
    protected $inmuebleCodigo;
    protected $inmueble;
    protected $inmuebleContenidos;

    protected $paginaCodigo;
    protected $pagina;
    protected $paginaContenidos;

    protected $idiomas;

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

        $this->idiomas = Translate::all();

        $inmuebleContenidos = Inmuebles_Contenidos::all(array(
            'inmueble_id' => $this->inmueble->id
        ));

        foreach ( $inmuebleContenidos as $inmuebleContenido )
            $this->inmuebleContenidos[$inmuebleContenido->idioma] = $inmuebleContenido;

        $paginaContenidos = Inmuebles_Paginas_Contenidos::all(array(
            'inmueble_pagina_id' => $this->pagina->id
        ));

        foreach ( $paginaContenidos as $paginaContenido )
            $this->paginaContenidos[$paginaContenido->idioma] = $paginaContenido;

        return true;
    }

    public function validation()
    {
        $validation = array();

        foreach ( $this->idiomas as $idioma )
        {
            $validation["nombre_$idioma"] = 'required[2:80]';
            $validation["titulo_$idioma"] = 'required[2:80]';

            if ( !( isset($this->paginaContenidos[$idioma]) ) )
                $validation["url_$idioma"] = 'code[2:200]';
        }

        return $validation;
    }

    public function get()
    {
        $nombre = array();
        $titulo = array();
        $url = array();

        foreach ( $this->idiomas as $idioma )
        {
            $nombre[$idioma] = Request::getPost("nombre_$idioma",
                isset($this->paginaContenidos[$idioma]) ? $this->paginaContenidos[$idioma]->nombre : ''
            );

            $titulo[$idioma] = Request::getPost("titulo_$idioma",
                isset($this->paginaContenidos[$idioma]) ? $this->paginaContenidos[$idioma]->titulo : ''
            );

            $url[$idioma] = Request::getPost("url_$idioma",
                isset($this->paginaContenidos[$idioma]) ? $this->paginaContenidos[$idioma]->url : ''
            );
        }

        return array(
            'idiomas'            => $this->idiomas,

            'inmuebleCodigo'     => $this->inmuebleCodigo,
            'inmueble'           => $this->inmueble,
            'inmuebleContenidos' => $this->inmuebleContenidos,

            'paginaCodigo'       => $this->paginaCodigo,
            'pagina'             => $this->pagina,
            'paginaContenidos'   => $this->paginaContenidos,

            'nombre'             => $nombre,
            'titulo'             => $titulo,
            'url'                => $url
        );
    }

    public function post()
    {
        foreach ( $this->idiomas as $idioma )
        {
            $insert = ( !( isset($this->paginaContenidos[$idioma]) ) );

            if ( $insert )
            {
                $this->paginaContenidos[$idioma] = new Inmuebles_Paginas_Contenidos();
                $this->paginaContenidos[$idioma]->inmueble_pagina_id = $this->pagina->id;
                $this->paginaContenidos[$idioma]->idioma = $idioma;
            }

            $this->paginaContenidos[$idioma]->nombre = Request::getPost("nombre_$idioma",
                $insert ? '' : $this->paginaContenidos[$idioma]->nombre
            );

            $this->paginaContenidos[$idioma]->titulo = Request::getPost("titulo_$idioma",
                $insert ? '' : $this->paginaContenidos[$idioma]->titulo
            );

            $this->paginaContenidos[$idioma]->url = Request::getPost("url_$idioma",
                $insert ? '' : $this->paginaContenidos[$idioma]->url
            );

            if ( $insert )
                $this->paginaContenidos[$idioma]->insert();
            else
                $this->paginaContenidos[$idioma]->update();
        }

        return "/admin/inmuebles/$this->inmuebleCodigo/paginas/$this->paginaCodigo?edited=1";
    }
}