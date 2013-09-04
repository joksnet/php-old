<?php

class Admin_Inmuebles_Agregar extends Controller
{
    protected $unique = true;

    protected $idiomas;
    protected $values;

    public function init()
    {
        if ( !( Session::getInstance()->usuario ) )
            return '/admin/ingresar';

        $new = '';

        $this->idiomas = Translate::all();

        if ( !( Request::hasPost('codigo') ) )
            $new = str_pad(Inmuebles::count() + 1, 4, '0', STR_PAD_LEFT);

        $this->values = array();
        $this->values['codigo'] = Request::getPost('codigo', $new);

        foreach ( $this->idiomas as $idioma )
        {
            $this->values["nombre_$idioma"] = Request::getPost("nombre_$idioma", '');
            $this->values["titulo_$idioma"] = Request::getPost("titulo_$idioma", '');
            $this->values["url_$idioma"] = Request::getPost("url_$idioma", '');
        }

        return true;
    }

    public function validation()
    {
        $validation = array();
        $validation['codigo'] = 'code[4:20]';

        foreach ( $this->idiomas as $idioma )
        {
            $validation["nombre_$idioma"] = 'required[2:80]';
            $validation["titulo_$idioma"] = 'required[2:80]';
            $validation["url_$idioma"] = 'code[2:200]';
        }

        return $validation;
    }

    public function get()
    {
        return array_merge($this->values, array(
            'unique'  => $this->unique,
            'idiomas' => $this->idiomas
        ));
    }

    public function post()
    {
        $add = new Inmuebles();
        $add->codigo = strtolower(
            $this->values['codigo']
        );

        if ( $add->queryAll() )
            return ( $this->unique = false ) && false;

        $id = $add->insert();

        foreach ( $this->idiomas as $idioma )
        {
            $addContenido = new Inmuebles_Contenidos();
            $addContenido->inmueble_id = $id;
            $addContenido->idioma      = $idioma;
            $addContenido->nombre      = $this->values["nombre_$idioma"];
            $addContenido->titulo      = $this->values["titulo_$idioma"];
            $addContenido->url         = strtolower($this->values["url_$idioma"]);
            $addContenido->insert();
        }

        return "/admin/inmuebles/$add->codigo?added=1";
    }
}