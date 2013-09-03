<?php

class Db
{
    const FETCH_ASSOC  = 0;
    const FETCH_ARRAY  = 1;

    /**
     * @var Db
     */
    protected static $instance;

    /**
     * @var mysqli
     */
    protected $connection;

    /**
     * @return Db
     */
    public static function getInstance()
    {
        if ( !( self::$instance instanceof Db ) )
            self::$instance = new Db();

        return self::$instance;
    }

    public function connect( $config )
    {
        $this->connection = @ new mysqli(
            $config['hostname'],
            $config['username'],
            $config['password'],
            $config['database']
        );

        if ( $this->connection->connect_errno )
            throw new Exception( $this->connection->connect_error );

        $this->execute("SET NAMES 'utf8'");
    }

    public function disconnect()
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

        throw new Exception( $this->connection->error );
    }

    public function inserted()
    {
        return $this->connection->insert_id;
    }

    public function affected()
    {
        return $this->connection->affected_rows;
    }

    public function fetch( $sql, $type = Db::FETCH_ASSOC, $offset = 0, $length = null )
    {
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

            call_user_func_array(
                array($stmt, 'bind_result'), $array
            );

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
                    case Db::FETCH_ASSOC:
                        $results[] = array_map(create_function('$a', 'return "$a";'), $assoc);
                        break;

                    case Db::FETCH_ARRAY:
                        $results[] = array_map(create_function('$a', 'return "$a";'), $array);
                        break;

                    default:
                        throw new Exception('Fetch type not supported');
                        break;
                }

                if ( $length > 0 && $i >= $offset + $length )
                    break;
            }

            $meta->close();
            $stmt->close();

            return $results;
        }

        throw new Exception( $this->connection->error );
    }

    public function fetchAssoc( $sql )
    {
        return $this->fetch($sql, Db::FETCH_ASSOC);
    }

    public function fetchArray( $sql )
    {
        return $this->fetch($sql, Db::FETCH_ARRAY);
    }

    public function fetchPairs( $sql )
    {
        $data = array();
        $rows = $this->fetch($sql, Db::FETCH_ARRAY);

        foreach ( $rows as $row )
        {
            $key   = $row[0];
            $value = $row[1];

            $data[$key] = $value;
        }

        return $data;
    }

    public function fetchColumn( $sql, $column = null )
    {
        $data = array();
        $rows = $this->fetch($sql, Db::FETCH_ASSOC);

        foreach ( $rows as $row )
        {
            if ( isset($row[$column]) )
                $data[] = $row[$column];
            else
                $data[] = array_shift($row);
        }

        return $data;
    }

    public function fetchOne( $sql )
    {
        $rows = $this->fetch($sql, Db::FETCH_ARRAY, 0, 1);
        $row = ( $rows ) ? $rows[0] : array();

        return array_shift($row);
    }

    public function fetchRow( $sql, $line = 0, $type = Db::FETCH_ASSOC )
    {
        $rows = $this->fetch($sql, $type, $line, 0, 1);
        $row = ( $rows ) ? $rows[0] : array();

        return $row;
    }

    protected function quoteIdentifier( $identifier, $alias = null )
    {
        $quoted = '';

        if ( strpos($identifier, '.') !== false )
            $identifier = explode('.', $identifier);

        if ( !( is_array($identifier) ) )
        {
            if ( preg_match('~^([A-Z]+)\((.*)\)$~', $identifier, $matches) )
                $quoted = "{$matches[1]}(`{$matches[2]}`)";
            else
                $quoted = ( '*' == $identifier ) ? '*' : "`$identifier`";
        }
        else
        {
            $segments = array();

            foreach ( $identifier as $segment )
                $segments[] = $this->quoteIdentifier($segment);

            if ( !( null === $alias ) && end($identifier) == $alias )
                $alias = null;

            $quoted = implode('.', $segments);
        }

        if ( !( null === $alias ) )
            $quoted .= ' AS ' . $this->quoteIdentifier($alias);

        return $quoted;
    }

    protected function quote( $value )
    {
        if ( is_int($value) )
            return $value;
        elseif ( is_float($value) )
            return sprintf('%F', $value);

        return "'" . addcslashes($value, "\000\n\r\\'\"\032") . "'";
    }

    protected function exprFields( $fields )
    {
        if ( is_array($fields) )
        {
            $array = array();

            foreach ( $fields as $alias => $field )
            {
                if ( is_numeric($alias) )
                    $array[] = $this->quoteIdentifier($field);
                else
                    $array[] = $this->quoteIdentifier($field, $alias);
            }

            return implode(', ', $array);
        }

        return $this->quoteIdentifier($fields);
    }

    protected function exprWhere( $where, $identifier = false )
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
                        $tmp[] = $this->quoteIdentifier($field) . ' = ' . ( $identifier ? $this->quoteIdentifier($value) : $this->quote($value) );
                    else
                    {
                        $values = array();

                        foreach ( $value as $valueCurrent )
                            $values[] = $identifier ? $this->quoteIdentifier($valueCurrent) : $this->quote($valueCurrent);

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

    protected function exprOrder( $field )
    {
        if ( is_array($field) )
        {
            $fields = array();

            if ( $field )
            {
                foreach ( $field as $key => $value )
                {
                    if ( $key )
                        $fields[] = $key . ' ' . ( ( $value == 'DESC' ) ? 'DESC' : 'ASC' );
                    else
                        $fields[] = $value;
                }

                return ' ORDER BY ' . implode(', ', $fields);
            }

            return '';
        }

        if ( is_string($field) )
            return " ORDER BY $field";

        return '';
    }

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

                return " LIMIT $start,$length";
            }

            return " LIMIT $start";
        }

        return " LIMIT $start,$length";
    }

    protected function exprJoin( $table, $where = null, $inner = true )
    {
        if ( !( $table ) )
            return '';

        $type = $inner === false ? 'LEFT' : 'INNER';

        return ' ' . $type . ' JOIN ' . $this->quoteIdentifier($table)
             . ' ON ' . $this->exprWhere($where);
    }

    protected function exprJoins( $joins )
    {
        if ( !( is_array($joins) ) )
            return '';

        $sql = '';

        foreach ( $joins as $join )
        {
            $table = null;
            $where = null;
            $inner = null;

            if ( isset($join['table']) ) $table = $join['table'];
            if ( isset($join['where']) ) $where = $join['where'];
            if ( isset($join['inner']) ) $inner = $join['inner'];

            $sql .= $this->exprJoin($table, $where, $inner);
        }

        return $sql;
    }

    public function select( $fields, $table, $where = null, $order = null, $limit = null, $joins = null )
    {
        return 'SELECT ' . $this->exprFields($fields)
             . ' FROM ' . $this->quoteIdentifier($table)
             . ' WHERE ' . $this->exprWhere($where)
             . $this->exprJoins($joins)
             . $this->exprOrder($order)
             . $this->exprLimit($limit);
    }

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

    public function delete( $table, $where = null )
    {
        $this->execute(
            'DELETE FROM ' . $this->quoteIdentifier($table) .
            ' WHERE ' . $this->exprWhere($where)
        );

        return $this->affected();
    }

    public function count( $table, $where = null )
    {
        return $this->fetchOne(
            'SELECT COUNT(*)' .
            ' FROM ' . $this->quoteIdentifier($table) .
            ' WHERE ' . $this->exprWhere($where)
        );
    }

    public function tables( $refresh = false )
    {
        static $tables = null;

        if ( null === $tables || $refresh )
            $tables = $this->fetchColumn('SHOW TABLES');

        return $tables;
    }

    public function truncate( $table )
    {
        $this->execute(
            'TRUNCATE ' . $this->quoteIdentifier($table)
        );

        return true;
    }
}