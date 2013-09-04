<?php

class Inmuebles_Paginas_Datos extends Model
{
    public static function one( $id )
    {
        return parent::_one(__CLASS__, $id);
    }

    public static function all( $where = null, $order = null, $limit = null )
    {
        return parent::_all(__CLASS__, $where, $order, $limit);
    }

    public static function allPairs( $where = null, $order = null, $limit = null )
    {
        $pairs = array();
        $datos = self::all($where, $order, $limit);

        foreach ( $datos as $dato )
            $pairs[$dato->nombre] = $dato;

        return $pairs;
    }

    public static function count( $where = null )
    {
        return parent::_count(__CLASS__, $where);
    }

    public static function destroy( $dato )
    {
        if ( !( $dato instanceof Inmuebles_Paginas_Datos ) )
            $dato = new Inmuebles_Paginas_Datos($dato);

        if ( !( $dato->found() ) )
            return false;

        return $dato->delete();
    }
}