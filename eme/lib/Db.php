<?php

class Db
{
    protected static $db = null;

    public static function open( $config )
    {
        self::$db = @mysql_connect(
            $config['hostname'],
            $config['username'],
            $config['password']
        );

        if ( self::$db )
            @mysql_select_db($config['database']);
    }

    public static function close()
    {
        if ( self::ready() )
            @mysql_close(self::$db);
    }

    public static function ready()
    {
        return ( self::$db ) ? true : false;
    }

    public static function update( $table, $fields, $where )
    {
        settype($table, 'string');
        settype($fields, 'array');

        if ( !( is_string($where) ) )
            $where = self::where($where);

        $set = array();

        foreach ( $fields as $name => $value )
            $set[] = "$name = '$value'";

        return self::query("UPDATE $table SET " . implode(', ', $set) . " WHERE $where");
    }

    public static function insert( $table, $fields )
    {
        $names = array_keys($fields);
        $values = array_map( create_function('$s', 'return "\'$s\'";'), array_values($fields) );

        return self::query("INSERT INTO $table ( " . implode(', ', $names) . " ) VALUES ( " . implode(', ', $values) . " )");
    }

    public static function count( $table, $where )
    {
        settype($table, 'string');

        if ( !( is_string($where) ) )
            $where = self::where($where);

        $count = self::query("SELECT COUNT(*) AS count FROM $table WHERE $where", false);

        if ( $count )
        {
            $count = array_shift($count);
            $count = $count['count'];

            return $count;
        }

        return 0;
    }

    public static function query( $sql )
    {
        $data = null;

        if ( !( $result = @mysql_query($sql) ) )
        {
            if ( defined('DEBUG') && DEBUG === true )
                var_dump( mysql_error() );

            return false;
        }

        if ( strncmp($sql, 'SELECT', 6) == 0 )
        {
            $data = array();
            {
                while ( $row = @mysql_fetch_assoc($result) )
                {
                    $data[] = $row;
                }
            }
        }
        elseif ( strncmp($sql, 'INSERT', 6) == 0 )
        {
            $data = @mysql_insert_id();
        }
        elseif ( strncmp($sql, 'UPDATE', 6) == 0 || strncmp($sql, 'DELETE', 6) == 0 )
        {
            $data = @mysql_affected_rows();
        }

        @mysql_free_result($result);

        if ( $data !== null )
            return $data;
        else
            return true;
    }

    public static function assoc( $sql, $field = null )
    {
        if ( strncmp($sql, 'SELECT', 6) != 0 )
            return false;

        if ( $result = self::query($sql) )
        {
            $data = array();

            foreach ( $result as $row )
            {
                if ( is_string($field) && isset($row[$field]) )
                {
                    $tmp = array( 0 => $row[$field] );
                    unset($row[$field]);
                }
                else
                    $tmp = array_values( array_slice($row, 0, 1) );

                $data[$tmp[0]] = $row;
            }

            return $data;
        }

        return false;
    }

    public static function column( $sql, $field = null )
    {
        if ( strncmp($sql, 'SELECT', 6) != 0 )
            return false;

        if ( $result = self::query($sql) )
        {
            $data = array();

            foreach ( $result as $row )
            {
                if ( is_string($field) && isset($row[$field]) )
                    $data[] = $row[$field];
                else
                    $data[] = array_shift($row);
            }

            return $data;
        }

        return false;
    }

    public static function pairs( $sql, $fieldKey = null, $fieldValue = null )
    {
        if ( strncmp($sql, 'SELECT', 6) != 0 )
            return false;

        if ( $result = self::query($sql) )
        {
            $data = array();

            foreach ( $result as $row )
            {
                if ( is_string($fieldKey) && isset($row[$fieldKey]) )
                    $key = $row[$fieldKey];
                else
                    $key = array_shift($row);

                if ( is_string($fieldValue) && isset($row[$fieldValue]) )
                    $value = $row[$fieldValue];
                else
                    $value = array_shift($row);

                $data[$key] = $value;
            }

            return $data;
        }

        return false;
    }

    public static function one( $sql, $field = null )
    {
        if ( strncmp($sql, 'SELECT', 6) != 0 )
            return false;

        if ( $result = self::query($sql) )
        {
            if ( is_string($field) && isset($result[0][$field]) )
                return $result[0][$field];
            else
                return array_shift( $result[0] );
        }

        return false;
    }

    public static function row( $sql, $line = 0 )
    {
        if ( strncmp($sql, 'SELECT', 6) != 0 )
            return false;

        if ( $result = self::query($sql) )
            return $result[$line];

        return false;
    }

    protected static function where( $where )
    {
        settype($where, 'array');

        $sql = array();

        foreach ( $where as $name => $value )
            $sql[] = "$name = $value";

        return implode(' AND ', $sql);
    }
}