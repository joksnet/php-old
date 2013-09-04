<?php

class Admin_Inmuebles_Configuracion extends Controller
{
    protected $codigo;
    protected $inmueble;
    protected $idiomas;
    protected $paginas;

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

        $this->paginas = Inmuebles_Paginas::allContenido(array(
            'inmueble_id' => $this->inmueble->id
        ));

        return true;
    }

    public function validation()
    {
        return array(
            'inicio'  => 'required',
            'diseno'  => 'required'
        );
    }

    public function get()
    {
        return array(
            'codigo'   => $this->codigo,
            'inmueble' => $this->inmueble,
            'idiomas'  => $this->idiomas,
            'paginas'  => $this->paginas,

            'inicio'   => Request::getPost('inicio', $this->inmueble->found() ? $this->inmueble->inmueble_pagina_id_inicio : 0),
            'lateral'  => Request::getPost('lateral', $this->inmueble->found() ? $this->inmueble->inmueble_pagina_id_lateral : 0),

            'diseno'   => Request::getPost('diseno', $this->inmueble->found() ? $this->inmueble->diseno : 0)
        );
    }

    public function post()
    {
        $this->inmueble->inmueble_pagina_id_inicio  = Request::getPost('inicio', $this->inmueble->found() ? $this->inmueble->inmueble_pagina_id_inicio : 0);
        $this->inmueble->inmueble_pagina_id_lateral = Request::getPost('lateral', $this->inmueble->found() ? $this->inmueble->inmueble_pagina_id_lateral : 0);

        $this->inmueble->diseno = Request::getPost('diseno', $this->inmueble->found() ? $this->inmueble->diseno : 0);
        $this->inmueble->update();

        return "/admin/inmuebles/$this->codigo?config=1";
    }
}