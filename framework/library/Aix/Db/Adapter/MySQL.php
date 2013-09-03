<?php
/**
 * Aix Framework
 * Copyright (c) 2009, Juan M Mart('i)nez
 */

/**
 * @see Aix_Db_Exception
 */
require_once 'Aix/Db/Exception.php';

/**
 * @package Aix_Db
 */
class Aix_Db_Adapter_MySQL extends Aix_Db_Adapter
{
    protected $default = array(
        'hostname' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => '',

        'charset'    => 'utf8',
        'persistent' => false
    );

    public function name()
    {
        return 'MySQL';
    }

    protected function connect()
    {
        $function = ( $this->config->persistent ) ? 'mysql_pconnect' : 'mysql_connect';

        $this->connection = @$function(
            $this->config->hostname,
            $this->config->username,
            $this->config->password
        );

        if ( mysql_errno($this->connection) )
            throw new Aix_Db_Exception( mysql_error($this->connection) );

        $this->database();
    }

    protected function database()
    {
        @mysql_select_db($this->config->database, $this->connection);

        if ( mysql_errno($this->connection) )
            throw new Aix_Db_Exception( mysql_error($this->connection) );
    }

    protected function disconnect()
    {
        @mysql_close($this->connection);
    }

    public function connected()
    {
        return ( $this->connection ) ? true : false;
    }

    public function execute( $sql )
    {
        if ( $result = @mysql_query($sql, $this->connection) )
            return true || @mysql_free_result($result);

        throw new Aix_Db_Exception( mysql_error($this->connection) );
    }

    public function inserted()
    {
        return @mysql_insert_id($this->connection);
    }

    public function affected()
    {
        return @mysql_affected_rows($this->connection);
    }

    public function fetch( $sql, $type = Aix_Db_Adapter::FETCH_ASSOC, $offset = 0, $length = null )
    {
        if ( !( $result = @mysql_query($sql, $this->connection) ) )
            throw new Aix_Db_Exception( mysql_error($this->connection) );

        switch ( $type )
        {
            case Aix_Db_Adapter::FETCH_ASSOC:
                $function = 'mysql_fetch_assoc';
                break;

            case Aix_Db_Adapter::FETCH_ARRAY:
                $function = 'mysql_fetch_row';
                break;

            case Aix_Db_Adapter::FETCH_OBJECT:
                $function = 'mysql_fetch_object';
                break;

            default:
                throw new Aix_Db_Exception('Fetch type not supported');
                break;
        }

        $results = array();

        $offset = ( null === $offset ) ? 0 : intval($offset);
        $length = ( null === $length ) ? 0 : intval($length);

        $i = -1;

        while ( $row = @$function($result) )
        {
            $i++;

            if ( $i < $offset )
                continue;

            $results[] = $row;

            if ( $length > 0 && $i >= $offset + $length )
                break;
        }

        if ( $result )
            @mysql_free_result($result);

        return $results;
    }
}