<?php

class Admin_Inmuebles_Fotos extends Controller
{
    protected $codigo;
    protected $inmueble;

    public function init( $codigo )
    {
        if ( !( Session::getInstance()->usuario ) )
            return '/admin/ingresar';

        $this->codigo = $codigo;

        $this->inmueble = new Inmuebles();
        $this->inmueble->codigo = $codigo;
        $this->inmueble->queryAll();

        return true;
    }

    public function get()
    {
        $page = Request::getQuery('page', 1);
        $per = Configuration::getInstance()->per;

        $start = ( $page - 1 ) * $per;

        $fotos = array();
        $count = 0;
        $pos = 0;

        if ( $this->inmueble->found() )
        {
            $fotos = Inmuebles_Fotos::all(array('inmueble_id' => $this->inmueble->id), array('posicion'), array($start, $per));
            $count = Inmuebles_Fotos::count(array('inmueble_id' => $this->inmueble->id));
            $pos   = Inmuebles_Fotos::pos($this->inmueble->id);
        }

        return array(
            'codigo'   => $this->codigo,
            'inmueble' => $this->inmueble,
            'fotos'    => $fotos,
            'count'    => $count,
            'start'    => $start,
            'page'     => $page,
            'per'      => $per,
            'pos'      => $pos
        );
    }
}