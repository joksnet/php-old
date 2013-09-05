<?php

function dhQuery( $query )
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

    return false;
}

function dhQueryInsert( $table, $fields )
{
    $names = array_keys($fields);
    $values = array_map( create_function('$s', 'return "\'$s\'";'), array_values($fields) );

    return dhQuery(
        "INSERT INTO $table ( " . implode(', ', $names) . " )
         VALUES ( " . implode(', ', $values) . " )"
    );
}

function dhQueryUpdate( $table, $fields, $where )
{
    $set = array();
    $where = dhWhereSQL($where);

    foreach ( $fields as $name => $value )
        $set[] = "$name = '$value'";

    return dhQuery(
        "UPDATE $table
         SET " . implode(', ', $set) . "
         WHERE $where"
    );
}

function dhQueryDelete( $table, $where )
{
    return dhQuery(
        "DELETE FROM $table
         WHERE " . dhWhereSQL($where)
    );
}

$db = @mysql_connect($dbConfig['hostname'], $dbConfig['username'], $dbConfig['password']);
      @mysql_select_db($dbConfig['database']);

function dhWhereSQL( $where )
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