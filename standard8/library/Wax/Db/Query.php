<?php

include_once 'Wax/Db.php';
include_once 'Wax/Db/Query/Exception.php';
include_once 'Wax/Db/Query/Select.php';
include_once 'Wax/Db/Query/Update.php';
include_once 'Wax/Db/Query/Delete.php';

class Wax_Db_Query
{
    private $_sql = '';
    private $_options = array(
        'autoFree'    => true,
        'fetchMethod' => 'Assoc'
    );

    public function __construct( $sql )
    {
        $this->_sql = $sql;
        $this->__prepare();

        if ( is_string($this->_sql) && strlen($this->_sql) > 0 )
        {
            $this->_result = @mysql_query($this->_sql);

            if ( !( $this->_result ) )
            {
                throw new Wax_Db_Query_Exception(
                    mysql_error()
                );

                exit();
            }
        }
        else
            return false;
    }

    private function __prepare()
    {
        $this->_sql = ltrim($this->_sql);
    }

    public function __toString()
    {
        return $this->_sql;
    }

    public function free()
    {
        if ( $this->_result )
            return @mysql_free_result($this->_result);
        else
            return false;
    }

    public function getInsertId()
    {
        return Db::getInsertId();
    }

    public function getAffectedRows()
    {
        return Db::getAffectedRows();
    }

    public function fetch()
    {
        $fetchMethod = 'fetch' . $this->_options['fetchMethod'];
        $fetch = $this->{$fetchMethod}();

        if ( $this->_options['autoFree'] )
            $this->free();

        return $fetch;
    }

    public function fetchAll()
    {
        $data = array();

        while ( $row = @mysql_fetch_array($this->_result, MYSQL_ASSOC) )
            $data[] = $row;

        if ( $this->_options['autoFree'] )
            $this->free();

        return $data;
    }

    public function fetchAssoc()
    {
        $data = array();

        while ( $row = @mysql_fetch_assoc($this->_result) )
        {
            $tmp = array_values(array_slice($row, 0, 1));
            $data[$tmp[0]] = $row;
        }

        if ( $this->_options['autoFree'] )
            $this->free();

        return $data;
    }

    public function fetchCol()
    {
        $data = array();

        while ( $row = @mysql_fetch_array($this->_result, MYSQL_NUM) )
            $data[] = $row[0];

        if ( $this->_options['autoFree'] )
            $this->free();

        return $data;
    }

    public function fetchPairs()
    {
        $data = array();

        while ( $row = @mysql_fetch_array($this->_result, MYSQL_NUM) )
            $data[$row[0]] = $row[1];

        if ( $this->_options['autoFree'] )
            $this->free();

        return $data;
    }

    public function fetchOne()
    {
        $row = @mysql_fetch_array($this->_result, MYSQL_NUM);

        if ( $this->_options['autoFree'] )
            $this->free();

        return $row[0];
    }

    public function fetchRow()
    {
        $row = @mysql_fetch_array($this->_result, MYSQL_ASSOC);

        if ( $this->_options['autoFree'] )
            $this->free();

        return ( $row ) ? $row : array();
    }

    public function numRows()
    {
        return @mysql_num_rows($this->_result);
    }

    public function dump()
    {
        echo $this->__toString();
    }
}