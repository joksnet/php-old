<?php

class Db
{
    public static $num = 0;

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

    public static function query( $sql )
    {
        $data = null;

        if ( !( $result = @mysql_query($sql) ) )
            return false;

        self::$num = @mysql_num_rows($result);

        if ( strncmp($sql, 'SELECT', 6) == 0 )
        {
            $data = array();

            if ( self::$num == 1 )
            {
                $data = @mysql_fetch_assoc($result);

                if ( sizeof($data) == 1 )
                {
                    $tmpk = array_values($data);
                    $data = $tmpk[0];
                }
            }
            else
            {
                while ( $row = @mysql_fetch_assoc($result) )
                {
                    $fc = array_slice($row, 0, 1);
                    $tmpk = array_keys($fc);
                    $tmpv = array_values($fc);

                    unset($row[$tmpk[0]]);

                    if ( sizeof($row) == 1 )
                        $data[$tmpv[0]] = array_pop($row);
                    else
                        $data[$tmpv[0]] = $row;
                }
            }
        }
        elseif ( strncmp($sql, 'INSERT', 6) == 0 )
        {
            $data = @mysql_insert_id();
        }
        elseif ( strncmp($sql, 'UPDATE', 6) == 0 )
        {
            $data = @mysql_affected_rows();
        }

        @mysql_free_result($result);

        if ( $data !== null )
            return $data;
        else
            return true;
    }
}