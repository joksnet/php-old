<?php

class Inmuebles extends Model
{
    public static function one( $id )
    {
        return parent::_one(__CLASS__, $id);
    }

    public static function oneContenido( $id, $idioma )
    {
        $inmueble = new Inmuebles();
        $result = Db::fetchAssoc(
            Db::select(
                array(
                    'inmuebles.inmueble_id',
                    'inmuebles.codigo',
                    'inmuebles_contenidos.inmueble_contenido_id',
                    'inmuebles_contenidos.idioma',
                    'inmuebles_contenidos.nombre',
                    'inmuebles_contenidos.url'
                ),
                'inmuebles', array(
                    'inmuebles.inmueble_id'       => $id,
                    'inmuebles_contenidos.idioma' => $idioma
                ), null, 1,
                array(
                    'inmuebles_contenidos', array(
                        'inmuebles_contenidos.inmueble_id' => 'inmuebles.inmueble_id'
                    )
                )
            )
        );

        if ( $result )
        {
            $inmueble->found(true);
            $inmueble->import(
                array_shift($result)
            );
        }

        return $inmueble;
    }

    public static function all( $where = null, $order = null, $limit = null )
    {
        return parent::_all(__CLASS__, $where, $order, $limit);
    }

    public static function allContenido( $where = null, $order = null, $limit = null, $idioma = null )
    {
        $result = Db::fetchAssoc(
            Db::select(
                array(
                    'inmuebles.inmueble_id',
                    'inmuebles.codigo',
                    'inmuebles.activo',
                    'inmuebles_contenidos.inmueble_contenido_id',
                    'inmuebles_contenidos.idioma',
                    'inmuebles_contenidos.nombre',
                    'inmuebles_contenidos.url',
                    'inmuebles_contenidos.descripcion'
                ),
                'inmuebles', $where, $order, $limit,
                array(
                    'inmuebles_contenidos', array(
                        'inmuebles_contenidos.inmueble_id' => 'inmuebles.inmueble_id'
                    )
                )
            )
        );

        if ( !( $result ) )
            return array();

        $inmuebles = array();

        $pos = 0;
        $len = sizeof($result);
        $row = $result[$pos];

        while ( $pos < $len )
        {
            $id     = $row['inmueble_id'];
            $codigo = $row['codigo'];
            $activo = $row['activo'];

            $contenidos = array();

            while ( $pos < $len && $id == $row['inmueble_id'] )
            {
                $contenido = new Inmuebles_Contenidos();
                $contenido->found(true);
                $contenido->import(array(
                    'inmueble_contenido_id' => $row['inmueble_contenido_id'],
                    'inmueble_id'           => $row['inmueble_id'],
                    'idioma'                => $row['idioma'],
                    'nombre'                => $row['nombre'],
                    'url'                   => $row['url'],
                    'descripcion'           => $row['descripcion']
                ));

                $contenidos[$row['idioma']] = $contenido;

                $pos++; $row = ( isset($result[$pos]) ) ? $result[$pos] : array();
            }

            $contenido = null;

            if ( isset($contenidos[$idioma]) )
                $contenido = $contenidos[$idioma];

            $inmueble = new Inmuebles();
            $inmueble->found(true);
            $inmueble->import(array(
                'inmueble_id' => $id,
                'codigo'      => $codigo,
                'activo'      => $activo,
                'contenidos'  => $contenidos,
                'contenido'   => $contenido
            ));

            $inmuebles[] = $inmueble;
        }

        return $inmuebles;
    }

    public static function allPaginas( $where = null, $idioma = null )
    {
        $result = Db::fetchAssoc(
            Db::select(
                array(
                    'inmuebles.inmueble_id',
                    'inmuebles.codigo',
                    'inmuebles.activo',
                    'inmuebles_contenidos.inmueble_contenido_id',
                    'inmuebles_contenidos.idioma',
                    'inmuebles_contenidos.nombre',
                    'inmuebles_contenidos.url',
                    'inmuebles_paginas.inmueble_pagina_id',
                    'pagina_codigo' => 'inmuebles_paginas.codigo',
                    'pagina_tipo'   => 'inmuebles_paginas.tipo',
                    'inmuebles_paginas_contenidos.inmueble_pagina_contenido_id',
                    'pagina_nombre' => 'inmuebles_paginas_contenidos.nombre',
                    'pagina_titulo' => 'inmuebles_paginas_contenidos.titulo',
                    'pagina_url'    => 'inmuebles_paginas_contenidos.url'
                ),
                'inmuebles', $where, array(
                    'inmuebles.inmueble_id' => 'ASC',
                    'inmuebles_paginas.posicion' => 'ASC'
                ), null,
                Db::join('inmuebles_contenidos', array(
                    'inmuebles_contenidos.inmueble_id' => 'inmuebles.inmueble_id'
                )) .
                Db::join('inmuebles_paginas', array(
                    'inmuebles_paginas.inmueble_id' => 'inmuebles.inmueble_id'
                )) .
                Db::join('inmuebles_paginas_contenidos', array(
                    'inmuebles_paginas_contenidos.inmueble_pagina_id' => 'inmuebles_paginas.inmueble_pagina_id',
                    'inmuebles_paginas_contenidos.idioma'             => 'inmuebles_contenidos.idioma'
                ))
            )
        );

        if ( !( $result ) )
            return array();

        $inmuebles = array();

        $pos = 0;
        $len = sizeof($result);
        $row = $result[$pos];

        while ( $pos < $len )
        {
            $id     = $row['inmueble_id'];
            $codigo = $row['codigo'];
            $activo = $row['activo'];

            $contenidos = array();
            $paginas = array();

            while ( $pos < $len && $id == $row['inmueble_id'] )
            {
                $current = $row['idioma'];

                $contenido = new Inmuebles_Contenidos();
                $contenido->found(true);
                $contenido->import(array(
                    'inmueble_contenido_id' => $row['inmueble_contenido_id'],
                    'inmueble_id'           => $row['inmueble_id'],
                    'idioma'                => $row['idioma'],
                    'nombre'                => $row['nombre'],
                    'url'                   => $row['url']
                ));

                $contenidos[$current] = $contenido;

                $paginaID     = $row['inmueble_pagina_id'];
                $paginaCodigo = $row['pagina_codigo'];
                $paginaTipo   = $row['pagina_tipo'];

                $paginaContenidos = array();

                while ( $pos < $len && $id == $row['inmueble_id'] && $paginaID == $row['inmueble_pagina_id'] )
                {
                    $current = $row['idioma'];

                    $paginaContenido = new Inmuebles_Paginas_Contenidos();
                    $paginaContenido->found(true);
                    $paginaContenido->import(array(
                        'inmueble_pagina_contenido_id' => $row['inmueble_pagina_contenido_id'],
                        'inmueble_pagina_id'           => $row['inmueble_pagina_id'],
                        'nombre'                       => $row['pagina_nombre'],
                        'titulo'                       => $row['pagina_titulo'],
                        'url'                          => $row['pagina_url']
                    ));

                    $paginaContenidos[$current] = $paginaContenido;

                    $pos++; $row = ( isset($result[$pos]) ) ? $result[$pos] : array();
                }

                $paginaContenido = null;

                if ( isset($paginaContenidos[$idioma]) )
                    $paginaContenido = $paginaContenidos[$idioma];

                $pagina = new Inmuebles_Paginas();
                $pagina->found(true);
                $pagina->import(array(
                    'inmueble_pagina_id' => $paginaID,
                    'codigo'             => $paginaCodigo,
                    'tipo'               => $paginaTipo,
                    'contenidos'         => $paginaContenidos,
                    'contenido'          => $paginaContenido
                ));

                $paginas[] = $pagina;
            }

            $contenido = null;

            if ( isset($contenidos[$idioma]) )
                $contenido = $contenidos[$idioma];

            $inmueble = new Inmuebles();
            $inmueble->found(true);
            $inmueble->import(array(
                'inmueble_id' => $id,
                'codigo'      => $codigo,
                'activo'      => $activo,
                'contenidos'  => $contenidos,
                'contenido'   => $contenido,
                'paginas'     => $paginas
            ));

            $inmuebles[] = $inmueble;
        }

        return $inmuebles;
    }

    public static function count( $where = null )
    {
        return parent::_count(__CLASS__, $where);
    }

    public static function destroy( $inmueble )
    {
        if ( !( $inmueble instanceof Inmuebles ) )
            $inmueble = new Inmuebles($inmueble);

        if ( !( $inmueble->found() ) )
            return false;

        $contenidos = Inmuebles_Contenidos::all(array('inmueble_id' => $inmueble->id));
        $paginas = Inmuebles_Paginas::all(array('inmueble_id' => $inmueble->id));
        $fotos = Inmuebles_Fotos::all(array('inmueble_id' => $inmueble->id));

        foreach ( $contenidos as $contenido )
            Inmuebles_Contenidos::destroy($contenido);

        foreach ( $paginas as $pagina )
            Inmuebles_Paginas::destroy($pagina);

        foreach ( $fotos as $foto )
            Inmuebles_Fotos::destroy($foto);

        foreach ( Inmuebles_Fotos::$sizes as $folder => $size )
        {
            if ( file_exists("upload/$inmueble->codigo/$folder/") )
                rmdir("upload/$inmueble->codigo/$folder/");
        }

        if ( file_exists("upload/$inmueble->codigo/") )
            rmdir("upload/$inmueble->codigo/");

        return $inmueble->delete();
    }
}