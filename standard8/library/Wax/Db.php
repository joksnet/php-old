<?php

include_once 'Wax/Db/Exception.php';
include_once 'Wax/Db/Query.php';

class Wax_Db
{
    const PLACEHOLDER = '?';
    const IDENTIFIER = '"';

    private static $_db = null;

    public static function open( $config )
    {
        self::$_db = @mysql_connect(
            $config['hostname'],
            $config['username'],
            $config['password']
        );

        if ( self::$_db )
            @mysql_select_db($config['database']);
    }

    public static function close()
    {
        if ( self::$_db )
            @mysql_close(self::$_db);
    }

    public static function ready()
    {
        return ( self::$_db ) ? true : false;
    }

    public static function quote( $value )
    {
        if ( is_array($value) )
        {
            foreach ( $value as $key => $val )
                $value[$key] = Db::quote($val);

            return implode(', ', $value);
        }
        else
            return self::IDENTIFIER . addslashes(trim($value)) . self::IDENTIFIER;
    }

    public static function quoteInto( $value, $bind = array() )
    {
        if ( func_num_args() > 2 )
        {
            $args = func_get_args();

            foreach ( $args as $key => $val )
            {
                if ( $key != 0 && strpos($value, self::PLACEHOLDER) )
                    $value = substr($value, 0, strpos($value, self::PLACEHOLDER))
                           . Db::quote($val)
                           . substr(strstr($value, self::PLACEHOLDER), 1);
            }

            return $value;
        }
        elseif ( is_array($bind) )
        {
            foreach ( $bind as $key => $val )
            {
                if ( is_string($key) )
                    $value = str_replace($key, Db::quote($val), $value);
                elseif ( strpos($value, self::PLACEHOLDER) )
                    $value = substr($value, 0, strpos($value, self::PLACEHOLDER))
                           . Db::quote($val)
                           . substr(strstr($value, self::PLACEHOLDER), 1);
            }

            return $value;
        }
        else
            return str_replace(self::PLACEHOLDER, Db::quote($bind), $value);
    }

    public static function quoteIdentifier( $value )
    {
        return str_replace(self::IDENTIFIER,
                           self::IDENTIFIER
                         . self::IDENTIFIER, $value);
    }

    public static function quoteIdentifierAs( $identifier, $alias = null )
    {
        if ( is_string($identifier) )
            $identifier = explode('.', $identifier);

        if ( is_array($identifier) )
        {
            $segments = array();

            foreach ( $identifier as $key => $segment )
            {
                if ( !( empty($key) ) && is_string($key) )
                    $segments[] = self::quoteIdentifier($key);
                $segments[] = self::quoteIdentifier($segment);
            }

            if ( $alias !== null && end($identifier) == $alias)
                $alias = null;

            $quoted = implode('.', $segments);
        }
        else
            $quoted = $this->quoteIdentifier($identifier);

        if ( $alias !== null )
            $quoted .= ' AS ' . self::quoteIdentifier($alias);

        return $quoted;
    }

    /**
     * @return Wax_Db_Query
     */
    public static function query( $sql )
    {
        if ( $sql instanceof Wax_Db_Query_Select )
        	$sql = $sql->__toString();
        if ( $sql instanceof Wax_Db_Query_Delete )
        	$sql = $sql->__toString();
        if ( $sql instanceof Wax_Db_Query_Update )
        	$sql = $sql->__toString();
        if ( $sql instanceof Wax_Db_Query_Insert )
        	$sql = $sql->__toString();

        return Wax_Factory::createObject('Wax_Db_Query', $sql);
    }

    /**
     * @return Wax_Db_Query_Delete
     */
    public static function delete( $table = '' )
    {
        return Wax_Factory::createObject('Wax_Db_Query_Delete', $table);
    }

    /**
     * @return Wax_Db_Query_Update
     */
    public static function update( $table = '' )
    {
        return Wax_Factory::createObject('Wax_Db_Query_Update', $table);
    }

    /**
     * @return Wax_Db_Query_Insert
     */
    public static function insert( $table = '' )
    {
        return Wax_Factory::createObject('Wax_Db_Query_Insert', $table);
    }

    /**
     * @return Wax_Db_Query_Select
     */
    public static function select()
    {
        return Wax_Factory::createObject('Wax_Db_Query_Select');
    }

    public static function getInsertId()
    {
        if ( self::$_db )
            return @mysql_insert_id(self::$_db);
        else
            return false;
    }

    public static function getAffectedRows()
    {
        if ( self::$_db )
            return @mysql_affected_rows(self::$_db);
        else
            return false;
    }
}