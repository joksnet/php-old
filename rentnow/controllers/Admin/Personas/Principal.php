<?php

class Admin_Personas_Principal extends Controller
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
            'personas' => Personas::all(null, array('inscripcion' => 'DESC'), array($start, $per)),
            'count'    => Personas::count(),
            'start'    => $start,
            'page'     => $page,
            'per'      => $per
        );
    }
}