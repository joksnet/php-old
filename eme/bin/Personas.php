<?php

class Personas extends WebBase
{
    public $type = 'text/html';

    protected $data = array();

    public function __construct()
    {
        parent::__construct();
    }

    public function dispatch()
    {
        $result = Db::query(
            "SELECT personas.id_personas
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
                  , empresas.id_empresas
                  , empresas.nombre AS empresa
                  , empresas.direccion_1
                  , empresas.direccion_2
                  , empresas.ciudad
                  , empresas.estado
                  , empresas.cod_postal
                  , empresas.web
                  , empresas.tel_oficina AS e_tel_oficina
                  , empresas.tel_fax AS e_tel_fax
                  , paises.id_paises
                  , paises.nombre AS pais
             FROM personas
             RIGHT JOIN empresas ON empresas.id_empresas = personas.id_empresas
             LEFT JOIN paises ON paises.id_paises = empresas.id_paises
             ORDER BY paises.nombre, empresas.nombre, personas.nombre"
        );

        if ( $result )
        {
            $i = 0; $row = $result[$i];

            while ( isset($result[$i]) )
            {
                $idEmpresas = $row['id_empresas'];

                $direccion  = String::format("{%s}{ %s}", $row['direccion_1'], $row['direccion_2']);
                $lugar      = String::format("{%s}{, %s}{ (%s)}", $row['ciudad'], $row['estado'], $row['cod_postal']);

                $this->data[$idEmpresas] = array(
                    'nombre'      => $row['empresa'],
                    'pais'        => $row['pais'],
                    'direccion'   => $direccion,
                    'lugar'       => $lugar,
                    'tel_oficina' => $row['e_tel_oficina'],
                    'tel_fax'     => $row['e_tel_fax'],
                    'web'         => $row['web'],
                    'personas'    => array()
                );

                while ( isset($result[$i]) && $idEmpresas == $row['id_empresas'] )
                {
                    $idPersonas = $row['id_personas'];

                    if ( $idPersonas > 0 )
                    {
                        $nombre     = String::format("{%s}{ %s}", $row['nombre'], $row['apellido']);
                        $telOficina = String::format("{%s}{ x%s}", $row['tel_oficina'], $row['tel_oficina_int']);

                        $this->data[$idEmpresas]['personas'][$idPersonas] = array(
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

                    $i++;

                    if ( isset($result[$i]) )
                        $row = $result[$i];
                }
            }
        }

        parent::dispatch();
    }
}