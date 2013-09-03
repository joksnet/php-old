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
 * @see Aix_Db_Adapter
 */
require_once 'Aix/Db/Adapter.php';

/**
 * @package Aix_Db
 */
class Aix_Db_Adapter_MySQLi extends Aix_Db_Adapter
{
    /**
     * @var mysqli
     */
    protected $connection;

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
        return 'MySQLi';
    }

    protected function connect()
    {
        $this->connection = @ new mysqli(
          ( $this->config->persistent ? 'p:' : '' )
          . $this->config->hostname,
            $this->config->username,
            $this->config->password,
            $this->config->database
        );

        if ( $this->connection->connect_errno )
            throw new Aix_Db_Exception( $this->connection->connect_error );
    }

    protected function disconnect()
    {
        $this->connection->close();
    }

    public function connected()
    {
        return ( !( $this->connection->connect_errno ) ) ? true : false;
    }

    public function execute( $sql )
    {
        if ( $result = $this->connection->query($sql) )
            return true || $result->close();

        throw new Aix_Db_Exception( $this->connection->error );
    }

    public function inserted()
    {
        return $this->connection->insert_id;
    }

    public function affected()
    {
        return $this->connection->affected_rows;
    }

    public function fetch( $sql, $type = Aix_Db_Adapter::FETCH_ASSOC, $offset = 0, $length = null )
    {
        if ( !( in_array( $type, array( Aix_Db_Adapter::FETCH_ASSOC, Aix_Db_Adapter::FETCH_ARRAY ) ) ) )
            throw new Aix_Db_Exception('Fetch type not supported');

        if ( $stmt = $this->connection->prepare($sql) )
        {
            $results = array();

            $stmt->execute();
            $stmt->store_result();

            $assoc = array();
            $array = array();

            $meta = $stmt->result_metadata();

            while ( $field = $meta->fetch_field() )
                $array[] = &$assoc[$field->name];

            call_user_func_array(array($stmt, 'bind_result'), $array);

            $offset = ( null === $offset ) ? 0 : intval($offset);
            $length = ( null === $length ) ? 0 : intval($length);

            $i = -1;

            while ( $stmt->fetch() )
            {
                $i++;

                if ( $i < $offset )
                    continue;

                switch ( $type )
                {
                    case Aix_Db_Adapter::FETCH_ASSOC:
                        $results[] = array_map(create_function('$a', 'return "$a";'), $assoc);
                        break;

                    case Aix_Db_Adapter::FETCH_ARRAY:
                        $results[] = array_map(create_function('$a', 'return "$a";'), $array);
                        break;

                    case Aix_Db_Adapter::FETCH_OBJECT:
                        // break;

                    default:
                        throw new Aix_Db_Exception('Fetch type not supported');
                        break;
                }

                if ( $length > 0 && $i >= $offset + $length )
                    break;
            }

            $meta->close();
            $stmt->close();

            return $results;
        }

        throw new Aix_Db_Exception( $this->connection->error );
    }
}