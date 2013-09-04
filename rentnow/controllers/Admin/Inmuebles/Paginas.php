<?php

class Admin_Inmuebles_Paginas extends Controller
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

        $paginas = array();
        $count = 0;
        $pos = 0;

        if ( $this->inmueble->found() )
        {
            $paginas = Inmuebles_Paginas::allContenido(array('inmueble_id' => $this->inmueble->id), array($start, $per));
            $count = Inmuebles_Paginas::count(array('inmueble_id' => $this->inmueble->id));
            $pos = Inmuebles_Paginas::pos($this->inmueble->id);
        }

        return array(
            'idiomas'  => Translate::all(),
            'codigo'   => $this->codigo,
            'inmueble' => $this->inmueble,
            'paginas'  => $paginas,
            'count'    => $count,
            'start'    => $start,
            'page'     => $page,
            'per'      => $per,
            'pos'      => $pos
        );
    }
}