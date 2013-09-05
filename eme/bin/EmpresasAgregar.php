<?php

class EmpresasAgregar extends WebBase
{
    public $type = 'text/html';

    protected $validationFlag = true;
    protected $validation = null;

    public function __construct( $argv = array() )
    {
        parent::__construct();
    }

    public function submit()
    {
        list($this->validationFlag, $this->validation) = Validation::check(array(
            'nombre' => 'required'
        ));

        if ( $this->validationFlag )
        {
            $nombre = Request::getPost('nombre');

            Db::insert('empresas', array(
                'nombre'         => $nombre,
                'fecha_creacion' => time()
            ));

            Response::setRedirect('/personas');
        }
    }
}