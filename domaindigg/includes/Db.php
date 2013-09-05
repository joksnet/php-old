<?php

class Db
{
    /**
     * Conects to the MySQL database.
     *
     * @param array $config
     */
    public static function connect( $config )
    {
        @mysql_connect($config['hostname'], $config['username'], $config['password']) or die( mysql_error() );
        @mysql_select_db($config['database']) or die( mysql_error() );
    }

    /**
     * Insert $fields in $table.
     *
     * @param string $table
     * @param array $fields
     */
    public static function insert( $table, $fields )
    {
        $fieldsNames  = array();
        $fieldsValues = array();

        foreach ( $fields as $name => $value )
        {
            $fieldsNames[]  = "$name";
            $fieldsValues[] = "'$value'";
        }

        $sql = "INSERT INTO $table ( " . implode(', ', $fieldsNames) . " )
                VALUES ( " . implode(', ', $fieldsValues) . " )";

        @mysql_query($sql) or die( mysql_error() );
    }

    /**
     * Query or fetch data.
     *
     * @param string $sql
     * @return array
     */
    public static function query( $sql )
    {
        $result = @mysql_query($sql) or die( mysql_error() );

        if ( strncmp($sql, 'SELECT', 6) != 0 )
            return array();

        if ( mysql_num_rows($result) > 0 )
        {
            $data = array();

            while ( $row = mysql_fetch_assoc($result) )
                $data[] = $row;

            return $data;
        }

        return array();
    }

    /**
     * Update a registry from $table.
     *
     * @param string $table
     * @param array $fields
     * @param string $where
     */
    public static function update( $table, $fields, $where )
    {
        $fieldsSet = array();

        foreach ( $fields as $name => $value )
            $fieldsSet[] = "$name='$value'";

        $sql = "UPDATE $table
                SET " . implode(', ', $fieldsSet) . "
                WHERE $where";

        @mysql_query($sql) or die( mysql_error() );
    }

    /**
     * Get last inserted id.
     *
     * @return integer
     */
    public static function inserted()
    {
        return @mysql_insert_id();
    }

    /**
     * Get a dictionary result.
     *
     * @param string $sql
     * @param integer $fieldKey
     * @param integer $fieldValue
     * @return array
     */
    public static function fetchPairs( $sql, $fieldKey = 0, $fieldValue = 1 )
    {
        $result = @mysql_query($sql) or die( mysql_error() );

        if ( strncmp($sql, 'SELECT', 6) != 0 )
            return array();

        if ( mysql_num_rows($result) > 0 )
        {
            $data = array();

            while ( $row = mysql_fetch_row($result) )
                $data[$row[$fieldKey]] = $row[$fieldValue];

            return $data;
        }

        return array();
    }

    /**
     * Return one field from the first row.
     *
     * @param string $sql
     * @param integet $field
     * @return mixed
     */
    public static function fetchOne( $sql, $field = 0 )
    {
        $result = @mysql_query($sql) or die( mysql_error() );

        if ( strncmp($sql, 'SELECT', 6) != 0 )
            return null;

        if ( mysql_num_rows($result) > 0 )
        {
            if ( $row = mysql_fetch_row($result) )
                return $row[$field];
        }

        return null;
    }

    /**
     * Return first row.
     *
     * @param string $sql
     * @return array
     */
    public static function fetchRow( $sql )
    {
        $result = @mysql_query($sql) or die( mysql_error() );

        if ( strncmp($sql, 'SELECT', 6) != 0 )
            return array();

        if ( mysql_num_rows($result) > 0 )
        {
            if ( $row = mysql_fetch_assoc($result) )
                return $row;
        }

        return array();
    }

    /**
     * Return a dictionary with the first field as key and the rest of value.
     *
     * @param string $sql
     * @return array
     */
    public static function fetchAssoc( $sql )
    {
        $result = @mysql_query($sql) or die( mysql_error() );

        if ( strncmp($sql, 'SELECT', 6) != 0 )
            return array();

        if ( mysql_num_rows($result) > 0 )
        {
            $data = array();

            while ( $row = mysql_fetch_assoc($result) )
            {
                $key = array_shift($row);
                $data[$key] = $row;
            }

            return $data;
        }

        return array();
    }
}