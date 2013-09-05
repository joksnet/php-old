<?php

include_once 'Wax/Db.php';
include_once 'Wax/Db/Query/Exception.php';

class Wax_Db_Query_Insert
{
    const MD5 = 1;

    public $_table = '';
    public $_fields = array();
    public $_values = array();

    public function __construct( $table = '' )
    {
        $this->from($table);
    }

    public function __toString()
    {
        return 'INSERT INTO ' . $this->_table
             . ' ( ' . implode(', ', $this->_fields) . ' )'
             . ' VALUES ( ' . implode(', ', $this->_values) . ' )'
             . ';';
    }

    /**
     * @return Wax_Db_Query_Insert
     */
    public function from( $table )
    {
        if ( strlen($table) > 0 )
            $this->_table = $table;
        return $this;
    }

    /**
     * @return Wax_Db_Query_Insert
     */
    public function set( $field, $value, $fn = null )
    {
        $this->_fields[] = $field;

        switch ( $fn )
        {
            case self::MD5:
                $this->_values[] = "MD5(" . Wax_Db::quote($value) . ")";
                break;
            default:
                $this->_values[] = Wax_Db::quote($value);
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