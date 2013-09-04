<?php

class Inmuebles_Paginas extends Model
{
    public function move( $new )
    {
        if ( !( $this->found ) )
            return false;

        if ( $this->posicion == $new )
            return false;

        if ( $this->posicion > $new )
        {
            Db::execute(
                "UPDATE `inmuebles_paginas`
                 SET `posicion` = `posicion` + 1
                 WHERE `posicion` < $this->posicion
                   AND `posicion` >= $new
                   AND `inmueble_id` = $this->inmueble_id"
            );
        }
        else
        {
            Db::execute(
                "UPDATE `inmuebles_paginas`
                 SET `posicion` = `posicion` - 1
                 WHERE `posicion` > $this->posicion
                   AND `posicion` <= $new
                   AND `inmueble_id` = $this->inmueble_id"
            );
        }

        $this->posicion = $new;
        $this->update();

        return true;
    }

    public static function one( $id )
    {
        return parent::_one(__CLASS__, $id);
    }

    public static function oneContenido( $id, $idioma )
    {
        $pagina = new Inmuebles_Paginas();
        $result = Db::fetchAssoc(
            Db::select(
                array(
                    'inmuebles_paginas.inmueble_pagina_id',
                    'inmuebles_paginas.inmueble_id',
                    'inmuebles_paginas.codigo',
                    'inmuebles_paginas.tipo',
                    'inmuebles_paginas.posicion',
                    'inmuebles_paginas_contenidos.inmueble_pagina_contenido_id',
                    'inmuebles_paginas_contenidos.idioma',
                    'inmuebles_paginas_contenidos.nombre',
                    'inmuebles_paginas_contenidos.url'
                ),
                'inmuebles_paginas', array(
                    'inmuebles_paginas.inmueble_pagina_id' => $id,
                    'inmuebles_paginas_contenidos.idioma'  => $idioma
                ), null, 1,
                array(
                    'inmuebles_paginas_contenidos', array(
                        'inmuebles_paginas_contenidos.inmueble_pagina_id' => 'inmuebles_paginas.inmueble_pagina_id'
                    )
                )
            )
        );

        if ( $result )
        {
            $pagina->found(true);
            $pagina->import(
                array_shift($result)
            );
        }

        return $pagina;
    }

    public static function all( $where = null, $order = null, $limit = null )
    {
        return parent::_all(__CLASS__, $where, $order, $limit);
    }

    public static function allContenido( $where = null, $limit = null )
    {
        $result = Db::fetchAssoc(
            Db::select(
                array(
                    'inmuebles_paginas.inmueble_pagina_id',
                    'inmuebles_paginas.codigo',
                    'inmuebles_paginas.tipo',
                    'inmuebles_paginas.menu',
                    'inmuebles_paginas.posicion',
                    'inmuebles_paginas_contenidos.inmueble_pagina_contenido_id',
                    'inmuebles_paginas_contenidos.idioma',
                    'inmuebles_paginas_contenidos.nombre',
                    'inmuebles_paginas_contenidos.url'
                ),
                'inmuebles_paginas', $where, array('posicion', 'inmueble_pagina_id'), $limit,
                array(
                    'inmuebles_paginas_contenidos', array(
                        'inmuebles_paginas_contenidos.inmueble_pagina_id' => 'inmuebles_paginas.inmueble_pagina_id'
                    )
                )
            )
        );

        if ( !( $result ) )
            return array();

        $paginas = array();

        $pos = 0;
        $len = sizeof($result);
        $row = $result[$pos];

        while ( $pos < $len )
        {
            $id       = $row['inmueble_pagina_id'];
            $codigo   = $row['codigo'];
            $tipo     = $row['tipo'];
            $menu     = $row['menu'];
            $posicion = $row['posicion'];

            $contenidos = array();

            while ( $pos < $len && $id == $row['inmueble_pagina_id'] )
            {
                $contenido = new Inmuebles_Paginas_Contenidos();
                $contenido->found(true);
                $contenido->import(array(
                    'inmueble_pagina_contenido_id' => $row['inmueble_pagina_contenido_id'],
                    'inmueble_pagina_id'           => $row['inmueble_pagina_id'],
                    'idioma'                       => $row['idioma'],
                    'nombre'                       => $row['nombre'],
                    'url'                          => $row['url']
                ));

                $contenidos[$row['idioma']] = $contenido;

                $pos++; $row = ( isset($result[$pos]) ) ? $result[$pos] : array();
            }

            $pagina = new Inmuebles_Paginas();
            $pagina->found(true);
            $pagina->import(array(
                'inmueble_pagina_id' => $id,
                'codigo'             => $codigo,
                'tipo'               => $tipo,
                'menu'               => $menu,
                'contenidos'         => $contenidos,
                'posicion'           => $posicion
            ));

            $paginas[] = $pagina;
        }

        return $paginas;
    }

    public static function count( $where = null )
    {
        return parent::_count(__CLASS__, $where);
    }

    public static function pos( $inmueble )
    {
        $pos = Db::fetchOne(
            Db::select(array('pos' => 'MAX(posicion)'), 'inmuebles_paginas', array('inmueble_id' => $inmueble))
        );

        if ( empty($pos) )
            $pos = 0;

        return $pos;
    }

    public static function destroy( $pagina )
    {
        if ( !( $pagina instanceof Inmuebles_Paginas ) )
            $pagina = new Inmuebles_Paginas($pagina);

        if ( !( $pagina->found() ) )
            return false;

        $contenidos = Inmuebles_Paginas_Contenidos::all(array('inmueble_pagina_id' => $pagina->id));

        foreach ( $contenidos as $contenido )
            Inmuebles_Paginas_Contenidos::destroy($contenido);

        $datos = Inmuebles_Paginas_Datos::all(array('inmueble_pagina_id' => $pagina->id));

        foreach ( $datos as $dato )
            Inmuebles_Paginas_Datos::destroy($dato);

        Db::execute(
            "UPDATE `inmuebles_paginas`
             SET `posicion` = `posicion` - 1
             WHERE `posicion` > $pagina->posicion
               AND `inmueble_id` = $pagina->inmueble_id"
        );

        return $pagina->delete();
    }
}