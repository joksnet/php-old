<?php

class Inmuebles_Paginas_Contenidos extends Model
{
    public static function one( $id )
    {
        return parent::_one(__CLASS__, $id);
    }

    public static function onePagina( $idPagina, $idioma = null )
    {
        if ( is_null($idioma) )
            $idioma = Translate::locale();

        $result = Db::fetchRow(
            Db::select(array(
                'inmuebles_paginas_contenidos.inmueble_pagina_contenido_id',
                'inmuebles_paginas_contenidos.idioma',
                'inmuebles_paginas_contenidos.nombre',
                'inmuebles_paginas_contenidos.titulo',
                'inmuebles_paginas_contenidos.url',
                'inmuebles_paginas.inmueble_pagina_id',
                'inmuebles_paginas.codigo',
                'inmuebles_paginas.tipo',
                'inmuebles_paginas.posicion'
            ), 'inmuebles_paginas_contenidos', array(
                'inmuebles_paginas.inmueble_pagina_id' => $idPagina,
                'inmuebles_paginas_contenidos.idioma'  => $idioma
            ), null, 1, array(
                'inmuebles_paginas', array('inmuebles_paginas.inmueble_pagina_id' => 'inmuebles_paginas_contenidos.inmueble_pagina_id')
            ))
        );

        $contenido = new Inmuebles_Paginas_Contenidos();

        if ( $result )
        {
            $contenido->found(true);
            $contenido->import($result);
        }

        return $contenido;
    }

    public static function all( $where = null, $order = null, $limit = null )
    {
        return parent::_all(__CLASS__, $where, $order, $limit);
    }

    public static function allContenidos( $where = null, $limit = null )
    {
        /**
        $return = array();
        $contenidos = self::all();

        foreach ( $contenidos as $contenido )
            $return[$contenido->idioma] = $contenido;

        return $return;
         */

        $result = Db::fetchAssoc(
            Db::select(
                array(
                    'inmuebles_paginas_contenidos.inmueble_pagina_contenido_id',
                    'inmuebles_paginas_contenidos.idioma',
                    'inmuebles_paginas_contenidos.nombre',
                    'inmuebles_paginas_contenidos.titulo',
                    'inmuebles_paginas_contenidos.url',
                    'inmuebles_paginas_contenidos_datos.inmueble_pagina_contenido_dato_id',
                    'dato_nombre'    => 'inmuebles_paginas_contenidos_datos.nombre',
                    'dato_contenido' => 'inmuebles_paginas_contenidos_datos.contenido'
                ),
                'inmuebles_paginas_contenidos', $where, array('inmueble_pagina_contenido_id'), $limit,
                array(
                    'inmuebles_paginas_contenidos_datos', array(
                        'inmuebles_paginas_contenidos_datos.inmueble_pagina_contenido_id' => 'inmuebles_paginas_contenidos.inmueble_pagina_contenido_id'
                    ), Db::JOIN_LEFT
                )
            )
        );

        if ( !( $result ) )
            return array();

        $contenidos = array();

        $pos = 0;
        $len = sizeof($result);
        $row = $result[$pos];

        while ( $pos < $len )
        {
            $id       = $row['inmueble_pagina_contenido_id'];
            $idioma   = $row['idioma'];
            $nombre   = $row['nombre'];
            $titulo   = $row['titulo'];
            $url      = $row['url'];

            $datos = array();

            while ( $pos < $len && $id == $row['inmueble_pagina_contenido_id'] )
            {
                /**
                $dato = new Inmuebles_Paginas_Contenidos_Datos();
                $dato->found(true);
                $dato->import(array(
                    'inmueble_pagina_contenido_dato_id' => $row['inmueble_pagina_contenido_dato_id'],
                    'inmueble_pagina_contenido_id'      => $row['inmueble_pagina_contenido_id'],
                    'nombre'                            => $row['dato_nombre'],
                    'contenido'                         => $row['dato_contenido']
                ));
                 */

                $datos[$row['dato_nombre']] = $row['dato_contenido'];

                $pos++; $row = ( isset($result[$pos]) ) ? $result[$pos] : array();
            }

            $contenido = new Inmuebles_Paginas_Contenidos();
            $contenido->found(true);
            $contenido->import(array(
                'inmueble_pagina_contenido_dato_id' => $id,
                'idioma' => $idioma,
                'nombre' => $nombre,
                'titulo' => $titulo,
                'url'    => $url,
                'datos'  => $datos
            ));

            $contenidos[$idioma] = $contenido;
        }

        return $contenidos;
    }

    public static function allPagina( $where = null, $order = null, $limit = null  )
    {
        $results = Db::fetchAssoc(
            Db::select(array(
                'inmuebles_paginas_contenidos.inmueble_pagina_contenido_id',
                'inmuebles_paginas_contenidos.idioma',
                'inmuebles_paginas_contenidos.nombre',
                'inmuebles_paginas_contenidos.titulo',
                'inmuebles_paginas_contenidos.url',
                'inmuebles_paginas.inmueble_pagina_id',
                'inmuebles_paginas.codigo',
                'inmuebles_paginas.tipo',
                'inmuebles_paginas.posicion'
            ), 'inmuebles_paginas_contenidos', $where, $order, $limit, array(
                'inmuebles_paginas', array('inmuebles_paginas.inmueble_pagina_id' => 'inmuebles_paginas_contenidos.inmueble_pagina_id')
            ))
        );

        $contenidos = array();

        foreach ( $results as $result )
        {
            $contenido = new Inmuebles_Paginas_Contenidos();
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
        if ( !( $contenido instanceof Inmuebles_Paginas_Contenidos ) )
            $contenido = new Inmuebles_Paginas_Contenidos($contenido);

        if ( !( $contenido->found() ) )
            return false;

        $datos = Inmuebles_Paginas_Contenidos_Datos::all(array('inmueble_pagina_contenido_id' => $contenido->id));

        foreach ( $datos as $dato )
            Inmuebles_Paginas_Contenidos_Datos::destroy($dato);

        return $contenido->delete();
    }
}