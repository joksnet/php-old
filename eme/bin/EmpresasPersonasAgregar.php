<?php

class EmpresasPersonasAgregar extends WebBase
{
    public $type = 'text/html';

    protected $idEmpresas = 0;
    protected $data = array();

    protected $validationFlag = true;
    protected $validation = null;

    public function __construct( $argv = array() )
    {
        parent::__construct();

        $this->idEmpresas = $argv[0];
    }

    public function submit()
    {
        list($this->validationFlag, $this->validation) = Validation::check(array(
            'nombre'   => 'required',
            'apellido' => 'required'
        ));

        if ( $this->validationFlag )
        {
            $nombre   = Request::getPost('nombre');
            $apellido = Request::getPost('apellido');
            $correo   = Request::getPost('correo');
            $cargo    = Request::getPost('cargo');

            Db::insert('personas', array(
                'id_empresas'    => $this->idEmpresas,

                'nombre'         => $nombre,
                'apellido'       => $apellido,
                'correo'         => $correo,
                'cargo'          => $cargo,

                'fecha_creacion' => time()
            ));

            Response::setRedirect("/empresas/$this->idEmpresas/personas");
        }
    }

    public function dispatch()
    {
        $row = Db::row(
            "SELECT empresas.id_empresas
                  , empresas.nombre
                  , empresas.direccion_1
                  , empresas.direccion_2
                  , empresas.ciudad
                  , empresas.estado
                  , empresas.cod_postal
                  , empresas.web
                  , empresas.tel_oficina
                  , empresas.tel_fax
                  , empresas.logo
             FROM empresas
             WHERE empresas.id_empresas = '$this->idEmpresas'"
        );

        $this->data = array(
            'nombre' => $row['nombre'],
            'logo'   => $row['logo']
        );

        parent::dispatch();
    }
}