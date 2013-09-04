<?php

class Db
{
    protected static $connection;

    const FETCH_ASSOC  = 0;
    const FETCH_ARRAY  = 1;

    const JOIN_INNER = 'INNER';
    const JOIN_LEFT  = 'LEFT';

    public static function connect( $config )
    {
        self::$connection = @ new mysqli(
            $config['hostname'],
            $config['username'],
            $config['password'],
            $config['database']
        );

        if ( self::$connection->connect_errno )
            throw new Exception( self::$connection->connect_error );

        self::execute("SET NAMES 'utf8'");
    }

    public static function disconnect()
    {
        self::$connection->close();
    }

    public static function connected()
    {
        return ( !( self::$connection->connect_errno ) ) ? true : false;
    }

    public static function execute( $sql )
    {
        if ( $result = self::$connection->query($sql) )
            return true || $result->close();

        throw new Exception( self::$connection->error );
    }

    public static function inserted()
    {
        return self::$connection->insert_id;
    }

    public static function affected()
    {
        return self::$connection->affected_rows;
    }

    public static function fetch( $sql, $type = Db::FETCH_ASSOC, $offset = 0, $length = null )
    {
        if ( $stmt = self::$connection->prepare($sql) )
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

        throw new Exception( self::$connection->error );
    }

    public static function fetchAssoc( $sql )
    {
        return self::fetch($sql, Db::FETCH_ASSOC);
    }

    public static function fetchArray( $sql )
    {
        return self::fetch($sql, Db::FETCH_ARRAY);
    }

    public static function fetchPairs( $sql )
    {
        $data = array();
        $rows = self::fetch($sql, Db::FETCH_ARRAY);

        foreach ( $rows as $row )
        {
            $key   = $row[0];
            $value = $row[1];

            $data[$key] = $value;
        }

        return $data;
    }

    public static function fetchColumn( $sql, $column = null )
    {
        $data = array();
        $rows = self::fetch($sql, Db::FETCH_ASSOC);

        foreach ( $rows as $row )
        {
            if ( isset($row[$column]) )
                $data[] = $row[$column];
            else
                $data[] = array_shift($row);
        }

        return $data;
    }

    public static function fetchOne( $sql )
    {
        $rows = self::fetch($sql, Db::FETCH_ARRAY, 0, 1);
        $row = ( $rows ) ? $rows[0] : array();

        return array_shift($row);
    }

    public static function fetchRow( $sql, $line = 0, $type = Db::FETCH_ASSOC )
    {
        $rows = self::fetch($sql, $type, $line, 0, 1);
        $row = ( $rows ) ? $rows[0] : array();

        return $row;
    }

    protected static function quoteIdentifier( $identifier, $alias = null )
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
                $segments[] = self::quoteIdentifier($segment);

            if ( !( null === $alias ) && end($identifier) == $alias )
                $alias = null;

            $quoted = implode('.', $segments);
        }

        if ( !( null === $alias ) )
            $quoted .= ' AS ' . self::quoteIdentifier($alias);

        return $quoted;
    }

    protected static function quote( $value )
    {
        if ( is_int($value) )
            return $value;
        elseif ( is_float($value) )
            return sprintf('%F', $value);

        return "'" . addcslashes($value, "\000\n\r\\'\"\032") . "'";
    }

    protected static function exprFields( $fields )
    {
        if ( is_array($fields) )
        {
            $array = array();

            foreach ( $fields as $alias => $field )
            {
                if ( is_numeric($alias) )
                    $array[] = self::quoteIdentifier($field);
                else
                    $array[] = self::quoteIdentifier($field, $alias);
            }

            return implode(', ', $array);
        }

        return self::quoteIdentifier($fields);
    }

    protected static function exprWhere( $where, $identifier = false )
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
                        $tmp[] = self::quoteIdentifier($field) . ' = ' . ( $identifier ? self::quoteIdentifier($value) : self::quote($value) );
                    else
                    {
                        $values = array();

                        foreach ( $value as $valueCurrent )
                            $values[] = $identifier ? self::quoteIdentifier($valueCurrent) : self::quote($valueCurrent);

                        $tmp[] = self::quoteIdentifier($field) . ' IN (' . implode(', ', $values) . ')';
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

    protected static function exprOrder( $field )
    {
        if ( is_array($field) )
        {
            $fields = array();

            foreach ( $field as $key => $value )
            {
                if ( $key )
                    $fields[] = $key . ' ' . ( ( $value == 'DESC' ) ? 'DESC' : 'ASC' );
                else
                    $fields[] = $value;
            }

            return ' ORDER BY ' . implode(', ', $fields);
        }

        if ( is_string($field) )
            return " ORDER BY $field";

        return '';
    }

    protected static function exprLimit( $start, $length = null )
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

    public static function select( $fields, $table, $where = null, $order = null, $limit = null, $joins = null )
    {
        return 'SELECT ' . self::exprFields($fields)
             . ' FROM ' . self::quoteIdentifier($table)
             . self::joins($joins)
             . ' WHERE ' . self::exprWhere($where)
             . self::exprOrder($order)
             . self::exprLimit($limit);
    }

    public static function joins( $joins )
    {
        if ( is_array($joins) )
        {
            $type = Db::JOIN_INNER;

            switch ( count($joins) )
            {
                case 3:
                    $type = array_pop($joins);

                case 2:
                    $where = array_pop($joins);
                    $table = array_pop($joins);

                    return self::join(
                        $table, $where, $type
                    );
            }
        }

        elseif ( is_string($joins) )
            return $joins;

        return '';
    }

    public static function join( $table, $where, $type = Db::JOIN_INNER )
    {
        return ' ' . $type . ' JOIN ' . self::quoteIdentifier($table)
             . ' ON ' . self::exprWhere($where, true);
    }

    public static function insert( $table, $fields )
    {
        $fieldsNames  = array();
        $fieldsValues = array();

        foreach ( $fields as $field => $value )
        {
            $fieldsNames[]  = self::quoteIdentifier($field);
            $fieldsValues[] = self::quote($value);
        }

        self::execute(
            'INSERT INTO ' . self::quoteIdentifier($table) .
            '        ( ' . implode(', ', $fieldsNames) . ' )' .
            ' VALUES ( ' . implode(', ', $fieldsValues) . ' )'
        );

        return self::inserted();
    }

    public static function update( $table, $fields, $where = null )
    {
        $fieldsSet = array();

        foreach ( $fields as $field => $value )
            $fieldsSet[] = self::quoteIdentifier($field) . '=' . self::quote($value);

        self::execute(
            'UPDATE ' . self::quoteIdentifier($table) .
            '   SET ' . implode(', ', $fieldsSet) .
            ' WHERE ' . self::exprWhere($where)
        );

        return self::affected();
    }

    public static function delete( $table, $where = null )
    {
        self::execute(
            'DELETE FROM ' . self::quoteIdentifier($table) .
            ' WHERE ' . self::exprWhere($where)
        );

        return self::affected();
    }

    public static function count( $table, $where = null )
    {
        return self::fetchOne(
            'SELECT COUNT(*)' .
            ' FROM ' . self::quoteIdentifier($table) .
            ' WHERE ' . self::exprWhere($where)
        );
    }

    public static function tables( $refresh = false )
    {
        static $tables = null;

        if ( null === $tables || $refresh )
            $tables = self::fetchFirst('SHOW TABLES');

        return $tables;
    }
}