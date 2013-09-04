<?php

class Admin_Inmuebles_Paginas_Agregar extends Controller
{
    protected $unique = true;
    protected $idiomas;

    protected $codigo;
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

    public function validation()
    {
        $validation = array();
        $validation['codigo'] = 'code[4:20]';
        $validation['tipo'] = 'required';

        foreach ( $this->idiomas as $idioma )
        {
            $validation["titulo_$idioma"] = 'required[2:80]';
            $validation["nombre_$idioma"] = 'required[2:80]';
            $validation["url_$idioma"] = 'code[2:200]';
        }

        return $validation;
    }

    public function get()
    {
        $new = '';

        $titulos = array();
        $nombres = array();
        $urls = array();

        foreach ( $this->idiomas as $idioma )
        {
            $titulos[$idioma] = Request::getPost("titulo_$idioma", '');
            $nombres[$idioma] = Request::getPost("nombre_$idioma", '');
            $urls[$idioma] = Request::getPost("url_$idioma", '');
        }

        if ( $this->inmueble->found() && !( Request::hasPost('codigo') ) )
            $new = str_pad(Inmuebles_Paginas::pos($this->inmueble->id) + 1, 4, '0', STR_PAD_LEFT);

        return array(
            'unique'   => $this->unique,
            'idiomas'  => $this->idiomas,

            'codigo'     => $this->codigo,
            'inmueble'   => $this->inmueble,
            'contenidos' => $this->contenidos,

            'pagina'   => Request::getPost('codigo', $new),
            'tipo'     => Request::getPost('tipo', 0),
            'titulos'  => $titulos,
            'nombres'  => $nombres,
            'urls'     => $urls
        );
    }

    public function post()
    {
        $add = new Inmuebles_Paginas();
        $add->inmueble_id = $this->inmueble->id;
        $add->codigo = strtolower(
            Request::getPost('codigo', '')
        );

        if ( $add->queryAll() )
            return ( $this->unique = false ) && false;

        $add->tipo = Request::getPost('tipo', '');
        $add->posicion = Inmuebles_Paginas::pos($this->inmueble->id) + 1;

        $id = $add->insert();

        foreach ( $this->idiomas as $idioma )
        {
            $addContenido = new Inmuebles_Paginas_Contenidos();
            $addContenido->inmueble_pagina_id = $id;
            $addContenido->idioma = $idioma;
            $addContenido->titulo = Request::getPost("titulo_$idioma", '');
            $addContenido->nombre = Request::getPost("nombre_$idioma", '');
            $addContenido->url    = strtolower(Request::getPost("url_$idioma", ''));
            $addContenido->insert();
        }

        return "/admin/inmuebles/$this->codigo/paginas/$add->codigo?added=1";
    }
}