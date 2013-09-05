<?php

class EmpresasEditar extends WebBase
{
    public $type = 'text/html';

    protected $idEmpresas = 0;
    protected $data = array();
    protected $paises = array();

    protected $validationFlag = true;
    protected $validation = null;

    public function __construct( $argv = array() )
    {
        parent::__construct();

        $this->idEmpresas = $argv[0];
        $this->paises = Db::pairs(
            "SELECT paises.id_paises
                  , paises.nombre
             FROM paises
             ORDER BY paises.nombre"
        );
    }

    public function submit()
    {
        if ( Request::hasPost('guardar') )
        {
            list($this->validationFlag, $this->validation) = Validation::check(array(
                'nombre' => 'required'
            ));

            if ( $this->validationFlag )
            {
                $nombre        = Request::getPost('nombre');
                $direccion1    = Request::getPost('direccion_1');
                $direccion2    = Request::getPost('direccion_2');
                $ciudad        = Request::getPost('ciudad');
                $estado        = Request::getPost('estado');
                $codPostal     = Request::getPost('cod_postal');
                $idPaises      = Request::getPost('id_paises');
                $web           = Request::getPost('web');
                $telOficina    = Request::getPost('tel_oficina');
                $telFax        = Request::getPost('tel_fax');

                Db::update('empresas', array(
                    'nombre' => $nombre,

                    'direccion_1'        => $direccion1,
                    'direccion_2'        => $direccion2,
                    'ciudad'             => $ciudad,
                    'estado'             => $estado,
                    'cod_postal'         => $codPostal,
                    'id_paises'          => $idPaises,

                    'web'                => $web,

                    'tel_oficina'        => $telOficina,
                    'tel_fax'            => $telFax,

                    'fecha_modificacion' => time(),
                ), "id_empresas = '$this->idEmpresas'");

                Response::setRedirect("/empresas/$this->idEmpresas");
            }
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
                  , paises.id_paises
                  , paises.nombre AS pais
             FROM empresas
             LEFT JOIN paises ON paises.id_paises = empresas.id_paises
             WHERE empresas.id_empresas = '$this->idEmpresas'"
        );

        $direccion  = String::format("{%s}{ %s}", $row['direccion_1'], $row['direccion_2']);
        $lugar      = String::format("{%s}{, %s}{ (%s)}", $row['ciudad'], $row['estado'], $row['cod_postal']);

        $this->data = array(
            'nombre'               => $row['nombre'],

            'direccion_1'          => $row['direccion_1'],
            'direccion_2'          => $row['direccion_2'],

            'direccion'            => $direccion,
            'lugar'                => $lugar,

            'id_paises'            => $row['id_paises'],
            'pais'                 => $row['pais'],
            'ciudad'               => $row['ciudad'],
            'estado'               => $row['estado'],
            'cod_postal'           => $row['cod_postal'],

            'tel_oficina'          => $row['tel_oficina'],
            'tel_fax'              => $row['tel_fax'],
            'web'                  => $row['web'],

            'logo'                 => $row['logo']
        );

        parent::dispatch();
    }
}