<?php
/**
 * Aix Framework
 * Copyright (c) 2009, Juan M Mart('i)nez
 */

/**
 * @see Aix_Config
 */
require_once 'Aix/Config.php';

/**
 * @package Aix_Db
 */
abstract class Aix_Db_Adapter
{
    /**
     * @var array
     */
    protected $default = array();

    /**
     * @var Aix_Config
     */
    protected $config;

    /**
     * @var object|resource
     */
    protected $connection;

    /**
     * Constantes utilizadas por `Aix_Db_Adapter->fetch()`.
     */
    const FETCH_ASSOC  = 0;
    const FETCH_ARRAY  = 1;
    const FETCH_OBJECT = 2;

    /**
     * @param array|Aix_Config $config
     */
    public function __construct( $config )
    {
        if ( $config instanceof Aix_Config )
            $config = $config->toArray();

        $this->config = new Aix_Config( array_merge($this->default, $config) );
        $this->connect();
    }

    public function __destruct()
    {
        $this->disconnect();
    }

    /**
     * Devuelve el nombre del `Aix_Db_Adapter`.
     *
     * @return string
     */
    abstract public function name();

    /**
     * Abre una conexi('o)n a la base de datos.
     */
    abstract protected function connect();

    /**
     * Cierra una conexi('o)n a la base de datos.
     */
    abstract protected function disconnect();

    /**
     * Devuelve `true` si se encuentra conectado. `False` de lo contrario.
     *
     * @return boolean
     */
    abstract public function connected();

    /**
     * Ejecuta una consulta. Esta funci('o)n no devuelve ning('u)n dato, solo
     * un valor booleano.
     *
     * @param string $sql
     * @return boolean
     */
    abstract public function execute( $sql );

    /**
     * ('U)ltimo identificador insertado.
     *
     * @return integer
     */
    abstract public function inserted();

    /**
     * Cantidad de registros afectados en la ('u)ltima consulta.
     *
     * @return integer
     */
    abstract public function affected();

    /**
     * @param string $sql
     * @param integer $type
     * @param integer $offset
     * @param integer $length
     * @return mixed
     */
    abstract public function fetch( $sql, $type = Aix_Db_Adapter::FETCH_ASSOC, $offset = 0, $length = null );

    /**
     * Obtiene registros en forma asociativa.
     *
     * @param string $sql
     * @return array
     */
    public function fetchAssoc( $sql )
    {
        return $this->fetch($sql, Aix_Db_Adapter::FETCH_ASSOC);
    }

    /**
     * Obtiene registros en una matriz num('e)rica.
     *
     * @param string $sql
     * @return array
     */
    public function fetchArray( $sql )
    {
        return $this->fetch($sql, Aix_Db_Adapter::FETCH_ARRAY);
    }

    /**
     * Obtiene registros en objectos.
     *
     * @param string $sql
     * @return array
     */
    public function fetchObject( $sql )
    {
        return $this->fetch($sql, Aix_Db_Adapter::FETCH_OBJECT);
    }

    /**
     * Obtiene los dos primeros campos en una matriz asociativa.
     *
     * @param string $sql
     * @return array
     */
    public function fetchPairs( $sql )
    {
        $data = array();
        $rows = $this->fetch($sql, Aix_Db_Adapter::FETCH_ARRAY);

        foreach ( $rows as $row )
        {
            $key   = $row[0];
            $value = $row[1];

            $data[$key] = $value;
        }

        return $data;
    }

    /**
     * Obtiene una ('u)nica columna en una matriz num('e)rica. Si el segundo
     * par('a)metro es nulo, entonces ser('a) la primer columna.
     *
     * @param string $sql
     * @param string $column
     * @return array
     */
    public function fetchColumn( $sql, $column = null )
    {
        $data = array();
        $rows = $this->fetch($sql, Aix_Db_Adapter::FETCH_ASSOC);

        foreach ( $rows as $row )
        {
            if ( isset($row[$column]) )
                $data[] = $row[$column];
            else
                $data[] = array_shift($row);
        }

        return $data;
    }

    /**
     * Obtiene el primero campo de la primera columna.
     *
     * @param string $sql
     * @return mixed
     */
    public function fetchOne( $sql )
    {
        $rows = $this->fetch($sql, Aix_Db_Adapter::FETCH_ARRAY, 0, 1);
        $row = ( $rows ) ? $rows[0] : array();

        return array_shift($row);
    }

    /**
     * Obtiene el primer registro.
     *
     * @param string $sql
     * @param integer $line
     * @param integer $type
     * @return array
     */
    public function fetchRow( $sql, $line = 0, $type = Aix_Db_Adapter::FETCH_ASSOC )
    {
        $rows = $this->fetch($sql, $type, $line, 1);
        $row = ( $rows ) ? $rows[0] : array();

        return $row;
    }

    /**
     * Devuelve el nombre del identificador (nombre de tabla o nombre de campo)
     * entre comillas invertidas. Se puede pasar un alias de la tabla como
     * segundo par('a)metro.
     *
     * @example
     * $adapter->quoteIdentifier('campo');          // `campo`
     * $adapter->quoteIdentifier('campo', 'alias'); // `campo` AS `alias`
     * $adapter->quoteIdentifier('db.tabla');       // `db`.`tabla`
     * $adapter->quoteIdentifier('db.tabla.campo'); // `db`.`tabla`.`campo`
     *
     * @param string $identifier
     * @param string|null $alias
     * @return string
     */
    protected function quoteIdentifier( $identifier, $alias = null )
    {
        $quoted = '';
        $identifier = explode('.', $identifier);

        if ( !( is_array($identifier) ) )
            $quoted = "`$identifier`";
        else
        {
            $segments = array();

            foreach ( $identifier as $segment )
                $segments[] = "`$segment`";

            if ( !( null === $alias ) && end($identifier) == $alias )
                $alias = null;

            $quoted = implode('.', $segments);
        }

        if ( !( null === $alias ) )
            $quoted .= ' AS ' . $this->quoteIdentifier($alias);

        return $quoted;
    }

    /**
     * Devuelve el valor entre comillas simples y agrega una contrabarra a los
     * caracteres especiales.
     *
     * @param string $value
     * @return string
     */
    protected function quote( $value )
    {
        if ( is_int($value) )
            return $value;
        elseif ( is_float($value) )
            return sprintf('%F', $value);

        return "'" . addcslashes($value, "\000\n\r\\'\"\032") . "'";
    }

    /**
     * Gener('a) la SQL de condici('o)n.
     *
     * @param array|string $where
     * @return string
     */
    protected function exprWhere( $where )
    {
        if ( is_array($where) )
        {
            $tmp = array();

            foreach ( $where as $field => $value )
            {
                if ( is_numeric( $field ) )
                    $tmp[] = $value;
                elseif ( !( empty( $field ) ) )
                {
                    if ( !( is_array($value) ) )
                        $tmp[] = $this->quoteIdentifier($field) . '=' . $this->quote($value);
                    else
                    {
                        $values = array();

                        foreach ( $value as $valueCurrent )
                            $values[] = $this->quote($valueCurrent);

                        $tmp[] = $this->quoteIdentifier($field) . ' IN (' . implode(', ', $values) . ')';
                    }
                }
            }

            if ( !( empty($tmp) ) )
                return implode(' AND ', $tmp);

            return '1';
        }

        if ( empty($where) )
            $where = '1';

        return $where;
    }

    /**
     * Gener('a) un ORDER BY como parte de sentencia de una consulta.
     *
     * @param arrray|string $field
     * @return string
     */
    protected function exprOrder( $field )
    {
        if ( is_array($field) )
        {
            $fields = array();

            foreach ( $field as $key => $value )
            {
                if ( $key )
                    $fields[] = $key . ' ' . ( ( $value == 'DESC' ) ? 'DESC' : 'ASC' );
                else
                    $field[] = $value;
            }

            return ' ORDER BY ' . implode(', ', $fields);
        }

        if ( is_string($field) )
            return " ORDER BY $field";

        return '';
    }

    /**
     * Gener('a) un LIMIT como parte de sentencia de una consulta.
     *
     * @param integer|array $start
     * @param integer|null $length
     * @return string
     */
    protected function exprLimit( $start, $length = null )
    {
        if ( empty($start) )
            return '';

        if ( null === $length )
        {
            if ( is_array($start) )
            {
                $length = array_pop($start);
                $start  = array_pop($start);

                return "LIMIT $start,$length";
            }

            return "LIMIT $start";
        }

        return "LIMIT $start,$length";
    }

    /**
     * Inserta un nuevo registro. Devuelve el nuevo identificador creado.
     *
     * @param string $table
     * @param array $fields
     * @return integer
     */
    public function insert( $table, $fields )
    {
        $fieldsNames  = array();
        $fieldsValues = array();

        foreach ( $fields as $field => $value )
        {
            $fieldsNames[]  = $this->quoteIdentifier($field);
            $fieldsValues[] = $this->quote($value);
        }

        $this->execute(
            'INSERT INTO ' . $this->quoteIdentifier($table) .
            '        ( ' . implode(', ', $fieldsNames) . ' )' .
            ' VALUES ( ' . implode(', ', $fieldsValues) . ' )'
        );

        return $this->inserted();
    }

    /**
     * Actualiza registros de una tabla. Devuelve la cantidad de registros
     * afectados.
     *
     * @param string $table
     * @param array $fields
     * @param array|string|null $where
     * @return integer
     */
    public function update( $table, $fields, $where = null )
    {
        $fieldsSet = array();

        foreach ( $fields as $field => $value )
            $fieldsSet[] = $this->quoteIdentifier($field) . '=' . $this->quote($value);

        $this->execute(
            'UPDATE ' . $this->quoteIdentifier($table) .
            '   SET ' . implode(', ', $fieldsSet) .
            ' WHERE ' . $this->exprWhere($where)
        );

        return $this->affected();
    }

    /**
     * Elimina registros de una tabla. Devuelve la cantidad de registros
     * eliminados de esta manera.
     *
     * @param string $table
     * @param array|string|null $where
     * @return integer
     */
    public function delete( $table, $where = null )
    {
        $this->execute(
            'DELETE FROM ' . $this->quoteIdentifier($table) .
            ' WHERE ' . $this->exprWhere($where)
        );

        return $this->affected();
    }

    /**
     * Obtiene una lista de tablas en la base de datos.
     *
     * @param boolean $refresh
     * @return array
     */
    public function tables( $refresh = false )
    {
        static $tables = null;

        if ( null === $tables || $refresh )
            $tables = $this->fetchFirst('SHOW TABLES');

        return $tables;
    }
}