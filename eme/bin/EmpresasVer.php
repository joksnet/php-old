<?php

class EmpresasVer extends WebBase
{
    public $type = 'text/html';

    protected $idEmpresas = 0;
    protected $data = array();

    public function __construct( $argv = array() )
    {
        parent::__construct();

        $this->idEmpresas = $argv[0];
    }

    public function dispatch()
    {
        $row = Db::row(
            "SELECT empresas.id_empresas
                  , empresas.nombre AS empresa
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
            'nombre' => $row['empresa'],
            'logo'   => $row['logo']
        );

        parent::dispatch();
    }
}