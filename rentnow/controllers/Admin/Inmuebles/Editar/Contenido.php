<?php

class Admin_Inmuebles_Editar_Contenido extends Controller
{
    protected $codigo;
    protected $idioma;
    protected $inmueble;
    protected $contenido;

    public function init( $codigo, $idioma )
    {
        if ( !( Session::getInstance()->usuario ) )
            return '/admin/ingresar';

        $this->codigo = $codigo;
        $this->idioma = $idioma;

        $this->inmueble = new Inmuebles();
        $this->inmueble->codigo = $codigo;
        $this->inmueble->queryAll();

        $this->contenido = new Inmuebles_Contenidos();

        if ( $this->inmueble->found() )
        {
            $this->contenido->inmueble_id = $this->inmueble->id;
            $this->contenido->idioma = $idioma;
            $this->contenido->queryAll();
        }

        return true;
    }

    public function validation()
    {
        $validation = array(
            'nombre'      => 'required[2:80]',
            'titulo'      => 'required[2:80]',
            'descripcion' => 'required'
        );

        if ( !( $this->contenido->found() ) )
            $validation['url'] = 'code[2:200]';

        return $validation;
    }

    public function get()
    {
        return array(
            'codigo'    => $this->codigo,
            'idioma'    => $this->idioma,
            'inmueble'  => $this->inmueble,
            'contenido' => $this->contenido,

            'nombre'      => Request::getPost('nombre', $this->contenido->found() ? $this->contenido->nombre : ''),
            'titulo'      => Request::getPost('titulo', $this->contenido->found() ? $this->contenido->titulo : ''),
            'url'         => Request::getPost('url', $this->contenido->found() ? $this->contenido->url : ''),
            'descripcion' => Request::getPost('descripcion', $this->contenido->found() ? $this->contenido->descripcion : '')
        );
    }

    public function post()
    {
        if ( $this->contenido->found() )
        {
            $this->contenido->nombre = Request::getPost('nombre', $this->contenido->nombre);
            $this->contenido->titulo = Request::getPost('titulo', $this->contenido->titulo);
            $this->contenido->descripcion = Request::getPost('descripcion', $this->contenido->descripcion);
            $this->contenido->update();
        }
        else
        {
            if ( !( $this->contenido->inmueble_id ) )
            {
                $this->contenido->inmueble_id = $this->inmueble->id;
                $this->contenido->idioma = $this->idioma;
            }

            $this->contenido->nombre = Request::getPost('nombre', '');
            $this->contenido->titulo = Request::getPost('titulo', '');
            $this->contenido->url = Request::getPost('url', '');
            $this->contenido->descripcion = Request::getPost('descripcion', '');
            $this->contenido->insert();
        }

        return "/admin/inmuebles/$this->codigo?edited=1";
    }
}