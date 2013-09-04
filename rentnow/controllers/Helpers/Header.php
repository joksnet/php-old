<?php

class Helpers_Header extends Controller
{
    protected $title;
    protected $options = array();

    public function init( $title = null, $options = array() )
    {
        $this->options = $options;

        if ( is_array($title) )
            $this->title = implode(' &raquo; ', array_reverse($title));
        elseif ( is_string($title) )
            $this->title = $title;

        return true;
    }

    public function get()
    {
        $configuracion = Configuration::getInstance();
        $idioma = Translate::locale();

        return array(
            'title'   => $this->title,
            'options' => $this->options,

            'nombre'      => $configuracion->nombre,
            'keywords'    => $configuracion->getContent('keywords', $idioma),
            'description' => $configuracion->getContent('description', $idioma)
        );
    }
}