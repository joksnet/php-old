<?php

class Admin_Configuracion extends Controller
{
    protected $config;

    public function init()
    {
        if ( !( Session::getInstance()->usuario ) )
            return '/admin/ingresar';

        $configuration = Configuration::getInstance();

        $this->config = array(
            'nombre'  => Request::getPost('nombre', $configuration->nombre),
            'idioma'  => Request::getPost('idioma', $configuration->idioma),
            'efecto'  => Request::getPost('efecto', $configuration->efecto),
            'twitter' => Request::getPost('twitter', $configuration->twitter),
            'per'     => Request::getPost('per', $configuration->per)
        );

        return true;
    }

    public function validation()
    {
        return array(
            'nombre'   => 'required',
            'idioma'   => 'required',
            'efecto'   => 'required',
            'per'      => 'number'
        );
    }

    public function get()
    {
        require_once 'controllers/Welcome.php';

        return array_merge($this->config, array(
            'idiomas' => Translate::all(),
            'efectos' => Welcome::$efectos
        ));
    }

    public function post()
    {
        $return = @file_put_contents('application.ini',
            Controller::dispatch('Helpers_Config', $this->config)
        );

        if ( false === $return )
            return '/admin/configuracion?error=1';

        return '/admin/configuracion?edited=1';
    }
}