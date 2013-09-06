<?php

/**
 * @author Juan M Martinez <joksnet@gmail.com>
 */
class Db
{
    const DEBUG = true;

    /**
     * @param string $query
     * @return mixed
     */
    public static function query( $query )
    {
        if ( $result = @mysql_query($query) )
        {
            switch ( substr($query, 0, 6) )
            {
                case 'SELECT':
                    $data = array();

                    while ( $row = mysql_fetch_assoc($result) )
                        $data[] = $row;

                    return $data;

                case 'UPDATE':
                case 'DELETE':
                    return @mysql_affected_rows();

                case 'INSERT':
                    return @mysql_insert_id();
            }
        }

        if ( self::DEBUG )
            var_dump( mysql_error() );

        return false;
    }

    /**
     * @param string $table
     * @param array $fields
     * @return integer
     */
    public static function insert( $table, $fields )
    {
        $names = array_keys($fields);
        $values = array_map( create_function('$s', 'return "\'$s\'";'), array_values($fields) );

        return self::query(
            "INSERT INTO $table ( " . implode(', ', $names) . " )
             VALUES ( " . implode(', ', $values) . " )"
        );
    }

    /**
     * @param string $table
     * @param array $fields
     * @param string|array $where
     * @return integer
     */
    public static function update( $table, $fields, $where )
    {
        $set = array();
        $where = self::whereSQL($where);

        foreach ( $fields as $name => $value )
            $set[] = "$name = '$value'";

        return self::query(
            "UPDATE $table
             SET " . implode(', ', $set) . "
             WHERE $where"
        );
    }

    /**
     * @param string $table
     * @param string|array $where
     * @return integer
     */
    public static function delete( $table, $where )
    {
        return self::query(
            "DELETE FROM $table
             WHERE " . self::whereSQL($where)
        );
    }

    public static function open()
    {
        global $db;
        global $dbConfig;

        $db = @mysql_connect($dbConfig['hostname'], $dbConfig['username'], $dbConfig['password']);
              @mysql_select_db($dbConfig['database']);
    }

    public static function close()
    {
        @mysql_close();
    }

    /**
     * @param string|array $where
     * @return string
     */
    public static function whereSQL( $where )
    {
        if ( is_array($where) )
        {
            $arrWhere = array();

            foreach ( $where as $name => $value )
                $arrWhere[] = "$name = '$value'";

            return implode(' AND ', $arrWhere);
        }

        if ( strlen($where) == 0 )
            $where = '1=1';

        return $where;
    }
}