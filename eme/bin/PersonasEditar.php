<?php

class PersonasEditar extends WebBase
{
    public $type = 'text/html';

    protected $idPersonas = 0;
    protected $data = array();

    protected $validationFlag = true;
    protected $validation = null;

    public function __construct( $argv = array() )
    {
        parent::__construct();

        $this->idPersonas = $argv[0];
    }

    public function submit()
    {
        if ( Request::hasPost('guardar') )
        {
            list($this->validationFlag, $this->validation) = Validation::check(array(
                'nombre'   => 'required',
                'apellido' => 'required'
            ));

            if ( $this->validationFlag )
            {
                $nombre        = Request::getPost('nombre');
                $apellido      = Request::getPost('apellido');
                $correo        = Request::getPost('correo');
                $cargo         = Request::getPost('cargo');

                $telOficina    = Request::getPost('tel_oficina');
                $telOficinaInt = Request::getPost('tel_oficina_int');
                $telCelular    = Request::getPost('tel_celular');
                $telFax        = Request::getPost('tel_fax');
                $telCasa       = Request::getPost('tel_casa');

                Db::update('personas', array(
                    'nombre'             => $nombre,
                    'apellido'           => $apellido,
                    'correo'             => $correo,
                    'cargo'              => $cargo,

                    'tel_oficina'        => $telOficina,
                    'tel_oficina_int'    => $telOficinaInt,
                    'tel_celular'        => $telCelular,
                    'tel_fax'            => $telFax,
                    'tel_casa'           => $telCasa,

                    'fecha_modificacion' => time()
                ), "id_personas = '$this->idPersonas'");

                Response::setRedirect("/personas/$this->idPersonas");
            }
        }
    }

    public function dispatch()
    {
        $row = Db::row(
            "SELECT personas.id_personas
                  , personas.nombre
                  , personas.apellido
                  , personas.correo
                  , personas.usuario
                  , personas.cargo
                  , personas.tel_oficina
                  , personas.tel_oficina_int
                  , personas.tel_celular
                  , personas.tel_fax
                  , personas.tel_casa
                  , personas.foto
                  , empresas.id_empresas
                  , empresas.nombre AS empresa
             FROM personas
             INNER JOIN empresas ON empresas.id_empresas = personas.id_empresas
             WHERE personas.id_personas = '$this->idPersonas'"
        );

        $nombreCompleto     = String::format("{%s}{ %s}", $row['nombre'], $row['apellido']);
        $telOficinaCompleto = String::format("{%s}{ x%s}", $row['tel_oficina'], $row['tel_oficina_int']);

        $this->data = array(
            'nombre_completo'      => $nombreCompleto,
            'nombre'               => $row['nombre'],
            'apellido'             => $row['apellido'],
            'cargo'                => $row['cargo'],
            'correo'               => $row['correo'],
            'usuario'              => $row['usuario'],

            'tel_oficina_completo' => $telOficinaCompleto,
            'tel_oficina'          => $row['tel_oficina'],
            'tel_oficina_int'      => $row['tel_oficina_int'],
            'tel_celular'          => $row['tel_celular'],
            'tel_fax'              => $row['tel_fax'],
            'tel_casa'             => $row['tel_casa'],

            'foto'                 => $row['foto']
        );

        parent::dispatch();
    }
}