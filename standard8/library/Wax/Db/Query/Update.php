<?php

include_once 'Wax/Db.php';
include_once 'Wax/Db/Query/Exception.php';

class Wax_Db_Query_Update
{
    const MD5 = 1;

    public $_table = '';
    public $_fields = array();
    public $_where = array();

    public function __construct( $table = '' )
    {
        $this->from($table);
    }

    public function __toString()
    {
        return 'UPDATE ' . $this->_table
             . ' SET ' . implode(', ', $this->_fields)
             . ( ( sizeof($this->_where) > 0 ) ? ' WHERE' . implode('', $this->_where) : '' )
             . ';';
    }

    /**
     * @return Wax_Db_Query_Update
     */
    public function from( $table )
    {
        if ( strlen($table) > 0 )
            $this->_table = $table;
        return $this;
    }

    /**
     * @return Wax_Db_Query_Update
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
     * @return Wax_Db_Query_Update
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
     * @return Wax_Db_Query_Update
     */
    public function set( $field, $value, $fn = null )
    {
        switch ( $fn )
        {
            case self::MD5:
                $this->_fields[] = "$field = MD5(" . Wax_Db::quote($value) . ")";
                break;
            default:
                $this->_fields[] = "$field = " . Wax_Db::quote($value);
                break;
        }

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