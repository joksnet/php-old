<?php

class Inmuebles_Contenidos extends Model
{
    public static function one( $id )
    {
        return parent::_one(__CLASS__, $id);
    }

    public static function all( $where = null, $order = null, $limit = null )
    {
        return parent::_all(__CLASS__, $where, $order, $limit);
    }

    public static function allInmueble( $where = null, $order = null, $limit = null  )
    {
        $results = Db::fetchAssoc(
            Db::select(array(
                'inmuebles_contenidos.inmueble_contenido_id',
                'inmuebles_contenidos.idioma',
                'inmuebles_contenidos.nombre',
                'inmuebles_contenidos.titulo',
                'inmuebles_contenidos.url',
                'inmuebles.inmueble_id',
                'inmuebles.inmueble_pagina_id_inicio',
                'inmuebles.inmueble_pagina_id_lateral',
                'inmuebles.codigo',
                'inmuebles.diseno'
            ), 'inmuebles_contenidos', $where, $order, $limit, array(
                'inmuebles', array('inmuebles.inmueble_id' => 'inmuebles_contenidos.inmueble_id')
            ))
        );

        $contenidos = array();

        foreach ( $results as $result )
        {
            $contenido = new Inmuebles_Contenidos();
            $contenido->found(true);
            $contenido->import($result);

            $contenidos[] = $contenido;
        }

        return $contenidos;
    }

    public static function count( $where = null )
    {
        return parent::_count(__CLASS__, $where);
    }

    public static function destroy( $contenido )
    {
        if ( !( $contenido instanceof Inmuebles_Contenidos ) )
            $contenido = new Inmuebles_Contenidos($contenido);

        if ( !( $contenido->found() ) )
            return false;

        return $contenido->delete();
    }
}