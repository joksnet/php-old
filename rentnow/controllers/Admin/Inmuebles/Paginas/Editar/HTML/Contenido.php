<?php

class Admin_Inmuebles_Paginas_Editar_HTML_Contenido extends Controller
{
    protected $idioma;

    protected $inmuebleCodigo;
    protected $inmueble;
    protected $inmuebleContenido;

    protected $paginaCodigo;
    protected $pagina;

    protected $contenido;
    protected $dato;

    public function init( $inmueble, $pagina, $idioma )
    {
        if ( !( Session::getInstance()->usuario ) )
            return '/admin/ingresar';

        $this->idioma = $idioma;

        $this->inmuebleCodigo = $inmueble;
        $this->inmueble = new Inmuebles();
        $this->inmueble->codigo = $inmueble;
        $this->inmueble->queryAll();

        if ( !( $this->inmueble->found() ) )
            return true;

        $this->inmuebleContenido = new Inmuebles_Contenidos();
        $this->inmuebleContenido->inmueble_id = $this->inmueble->id;
        $this->inmuebleContenido->idioma = $this->idioma;
        $this->inmuebleContenido->queryAll();

        $this->paginaCodigo = $pagina;
        $this->pagina = new Inmuebles_Paginas();
        $this->pagina->inmueble_id = $this->inmueble->id;
        $this->pagina->codigo = $pagina;
        $this->pagina->queryAll();

        if ( !( $this->pagina->found() ) )
            return true;

        if ( 'html' !== $this->pagina->tipo )
            return "/admin/inmuebles/$inmueble/paginas/$pagina/editar";

        $this->contenido = new Inmuebles_Paginas_Contenidos();
        $this->contenido->inmueble_pagina_id = $this->pagina->id;
        $this->contenido->idioma = $idioma;
        $this->contenido->queryAll();

        $this->dato = new Inmuebles_Paginas_Contenidos_Datos();
        $this->dato->nombre = 'html';

        if ( $this->contenido->found() )
        {
            $this->dato->inmueble_pagina_contenido_id = $this->contenido->id;
            $this->dato->queryAll();
        }

        return true;
    }

    public function validation()
    {
        $validation = array();
        $validation['html'] = 'required';

        if ( !( $this->contenido->found() ) )
        {
            $validation['nombre'] = 'required[2:80]';
            $validation['titulo'] = 'required[2:80]';
            $validation['url'] = 'code[2:200]';
        }

        return $validation;
    }

    public function get()
    {
        return array(
            'idioma'            => $this->idioma,

            'inmuebleCodigo'    => $this->inmuebleCodigo,
            'inmueble'          => $this->inmueble,
            'inmuebleContenido' => $this->inmuebleContenido,

            'paginaCodigo'      => $this->paginaCodigo,
            'pagina'            => $this->pagina,

            'contenido'         => $this->contenido,
            'dato'              => $this->dato,

            'nombre'            => Request::getPost('nombre', $this->contenido->found() ? $this->contenido->nombre : ''),
            'titulo'            => Request::getPost('titulo', $this->contenido->found() ? $this->contenido->titulo : ''),
            'url'               => Request::getPost('url', $this->contenido->found() ? $this->contenido->url : ''),
            'html'              => Request::getPost('html', $this->dato->found() ? $this->dato->contenido : '')
        );
    }

    public function post()
    {
        if ( $this->contenido->found() )
            $id = $this->contenido->id;
        else
        {
            $this->contenido->nombre = Request::getPost('nombre', '');
            $this->contenido->titulo = Request::getPost('titulo', '');
            $this->contenido->url = Request::getPost('url', '');

            $id = $this->contenido->insert();
        }

        if ( $this->dato->found() )
        {
            $html = Request::getPost('html', $this->dato->contenido);
            $html = htmlspecialchars($html);

            $this->dato->contenido = $html;
            $this->dato->update();
        }
        else
        {
            $html = Request::getPost('html', '');
            $html = htmlspecialchars($html);

            $this->dato->inmueble_pagina_contenido_id = $id;
            $this->dato->contenido = $html;
            $this->dato->insert();
        }

        return "/admin/inmuebles/$this->inmuebleCodigo/paginas/$this->paginaCodigo/editar/html?edited=$this->idioma";
    }
}