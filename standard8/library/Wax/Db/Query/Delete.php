<?php

include_once 'Wax/Db.php';
include_once 'Wax/Db/Query/Exception.php';

class Wax_Db_Query_Delete
{
    public $_table = '';
    public $_where = array();

    public function __construct( $table = '' )
    {
        $this->from($table);
    }

    public function __toString()
    {
        return 'DELETE FROM ' . $this->_table
             . ( ( sizeof($this->_where) > 0 ) ? ' WHERE' . implode('', $this->_where) : '' )
             . ';';
    }

    /**
     * @return Wax_Db_Query_Delete
     */
    public function from( $table )
    {
        if ( strlen($table) > 0 )
            $this->_table = $table;

        return $this;
    }

    /**
     * @return Wax_Db_Query_Delete
     */
    public function where( $condition )
    {
        $num = func_num_args();

        if ( $num == 2 )
        {
            $value = func_get_arg(1);

            if ( is_array($value) || strpos($condition, Wax_Db::PLACEHOLDER) )
                $condition = Wax_Db::quoteInto($condition, $value);
            else
                $condition = "$condition = " . Wax_Db::quote($value);
        }
        elseif ( $num > 1 )
        {
            $bind = func_get_args(); array_shift($bind);
            $condition = Wax_Db::quoteInto($condition, $bind);
        }

        if ( sizeof($this->_where) != 0 )
            $this->_where[] = " AND ( $condition )";
        else
            $this->_where[] = " ( $condition )";

        return $this;
    }

    /**
     * @return Wax_Db_Query_Delete
     */
    public function orWhere( $condition )
    {
        $num = func_num_args();

        if ( $num == 2 )
        {
            $value = func_get_arg(1);

            if ( is_array($value) || strpos($condition, Wax_Db::PLACEHOLDER) )
                $condition = Wax_Db::quoteInto($condition, $value);
            else
                $condition = "$condition = " . Wax_Db::quote($value);
        }
        elseif ( $num > 1 )
        {
            $bind = func_get_args(); array_shift($bind);
            $condition = Wax_Db::quoteInto($condition, $bind);
        }

        if ( sizeof($this->_where) != 0 )
            $this->_where[] = " OR ( $condition )";
        else
            $this->_where[] = " ( $condition )";

        return $this;
    }

    /**
     * @return Wax_Db_Query
     */
    public function query()
    {
        return Wax_Db::query($this->__toString());
    }
}