<?php

class Admin_Configuracion_Contenido extends Controller
{
    protected $idioma;
    protected $config;

    protected $title;
    protected $keywords;
    protected $description;

    public function init( $idioma )
    {
        if ( !( Session::getInstance()->usuario ) )
            return '/admin/ingresar';

        $this->idioma = $idioma;

        $this->config = Configuration::getInstance();
        $this->config->setIdioma($this->idioma);

        $this->title       = $this->config->getContent('title');
        $this->keywords    = $this->config->getContent('keywords');
        $this->description = $this->config->getContent('description');

        return true;
    }

    public function get()
    {
        return array(
            'idioma'      => $this->idioma,

            'title'       => Request::getPost('title', $this->title),
            'keywords'    => Request::getPost('keywords', $this->keywords),
            'description' => Request::getPost('description', $this->description)
        );
    }

    public function post()
    {
        $nombres = array(
            'title', 'keywords', 'description'
        );

        $title       = Request::getPost('title', $this->title);
        $keywords    = Request::getPost('keywords', $this->keywords);
        $description = Request::getPost('description', $this->description);

        foreach ( $nombres as $nombre )
        {
            if ( null === $this->$nombre )
                Db::insert('configuracion', array( 'idioma' => $this->idioma, 'nombre' => $nombre, 'valor' => $$nombre ));
            else
                Db::update('configuracion', array( 'idioma' => $this->idioma, 'valor' => $$nombre ), array( 'nombre' => $nombre ));
        }

        return "/admin/configuracion/$this->idioma?edited=1";
    }
}