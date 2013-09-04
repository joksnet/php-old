<?php

class Personas extends Model
{
    public static function one( $id )
    {
        return parent::_one(__CLASS__, $id);
    }

    public static function all( $where = null, $order = null, $limit = null )
    {
        return parent::_all(__CLASS__, $where, $order, $limit);
    }

    public static function count( $where = null )
    {
        return parent::_count(__CLASS__, $where);
    }
}