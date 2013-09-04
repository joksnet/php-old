<?php

class Admin_Header extends Controller
{
    protected $title = null;

    public function init( $title = null )
    {
        if ( is_array($title) )
            $this->title = implode(' &raquo; ', array_reverse($title));
        elseif ( is_string($title) )
            $this->title = $title;

        return true;
    }

    public function get()
    {
        $configuracion = Configuration::getInstance();
        $current = substr(Router::$current, strpos(Router::$current, '_') + 1);

        if ( version_compare(PHP_VERSION, '5.3.0') >= 0 )
            $parent = strstr($current, '_', true);
        else
            $parent = substr($current, 0, strpos($current, '_'));

        return array(
            'parent'  => $parent,
            'current' => $current,

            'title'   => $this->title,
            'nombre'  => $configuracion->nombre
        );
    }
}