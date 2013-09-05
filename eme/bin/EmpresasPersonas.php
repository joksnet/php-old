<?php

class EmpresasPersonas extends WebBase
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
        $result = Db::query(
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
                  , personas.id_personas
                  , personas.nombre
                  , personas.apellido
                  , personas.correo
                  , personas.cargo
                  , personas.tel_oficina
                  , personas.tel_oficina_int
                  , personas.tel_celular
                  , personas.tel_fax
                  , personas.tel_casa
                  , personas.foto
             FROM empresas
             LEFT JOIN personas ON personas.id_empresas = empresas.id_empresas
             WHERE empresas.id_empresas = '$this->idEmpresas'"
        );

        if ( $result )
        {
            $this->data = array(
                'nombre'   => $result[0]['empresa'],
                'personas' => array()
            );

            foreach ( $result as $row )
            {
                $idPersonas = $row['id_personas'];

                if ( $idPersonas > 0 )
                {
                    $nombre     = String::format("{%s}{ %s}", $row['nombre'], $row['apellido']);
                    $telOficina = String::format("{%s}{ x%s}", $row['tel_oficina'], $row['tel_oficina_int']);

                    $this->data['personas'][$idPersonas] = array(
                        'nombre' => $nombre,
                        'correo' => $row['correo'],
                        'cargo'  => $row['cargo'],

                        'tel_oficina' => $telOficina,
                        'tel_celular' => $row['tel_celular'],
                        'tel_fax'     => $row['tel_fax'],
                        'tel_casa'    => $row['tel_casa'],

                        'foto'        => $row['foto']
                    );
                }
            }
        }

        parent::dispatch();
    }
}