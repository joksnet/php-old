<?php

class Admin_Inmuebles_Principal extends Controller
{
    public function init()
    {
        if ( !( Session::getInstance()->usuario ) )
            return '/admin/ingresar';

        return true;
    }

    public function get()
    {
        $page = Request::getQuery('page', 1);
        $per = Configuration::getInstance()->per;

        $start = ( $page - 1 ) * $per;

        return array(
            'idiomas'   => Translate::all(),
            'inmuebles' => Inmuebles::allContenido(null, array('codigo'), array($start, $per)),
            'count'     => Inmuebles::count(),
            'start'     => $start,
            'page'      => $page,
            'per'       => $per
        );
    }
}