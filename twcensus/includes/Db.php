<?php

class Db
{
    /**
     * @var mysqli
     */
    protected static $db = null;

    /**
     * Número de Consultas hechas a la base de datos.
     *
     * @var integer
     */
    protected static $_queryCount = 0;

    const FETCH_ASSOC = 0;
    const FETCH_ARRAY = 1;
    const FETCH_PAIRS = 2;
    const FETCH_FIRST = 3;

    const FETCH_ONE = 4;
    const FETCH_ROW = 5;

    /**
     * Abre la conexión.
     * La configuración debería contener los siguientes campos:
     *    hostname = equipo a cual conectarse
     *    username = usuario
     *    password = clave de acceso
     *    database = nombre de la base de datos
     *
     * @param array $config
     */
    public static function connect( $config )
    {
        self::$db = @ new mysqli(
            $config['hostname'],
            $config['username'],
            $config['password'],
            $config['database']
        );

        if ( self::$db->connect_errno )
            throw new Exception(self::$db->connect_error, self::$db->connect_errno);
    }

    /**
     * Cierra la conexión.
     */
    public static function disconnect()
    {
        self::$db->close();
    }

    /**
     * Ejecuta una consulta. Esta función no devuelve ningún dato. Devuelve TRUE
     * en caso de éxito y FALSE en cualquier otro caso.
     *
     * @param string $sql
     * @return boolean
     */
    public static function query( $sql )
    {
        self::$_queryCount++;

        if ( $result = self::$db->query($sql) )
            return true || $result->close();

        return false;
    }

    /**
     * Devuelve el número de consultas hechas a la base de datos.
     */
    public static function queryCount()
    {
        return self::$_queryCount;
    }

    protected static function quote( $string )
    {
        return "'" . addslashes($string) . "'";
    }

    protected static function quoteInto( $sql )
    {
        if ( func_num_args() == 1 )
            return $sql;

        $sql = new String($sql);

        $args = func_get_args();
        $args = array_slice($args, 1);

        foreach ( $args as $i => $arg )
            $sql->{$i} = $arg;

        return $sql->__toString();
    }

    /**
     * Genera la SQL de condición.
     *
     * @param string|array $where
     * @return string
     */
    public static function where( $where )
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
                    if ( is_array($value) )
                        $tmp[] = "$field IN ('" . implode("', '", $value) . "')";
                    else
                        $tmp[] = "$field=" . self::quote($value);
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
     * Genera un LIMIT como parte de sentencia una consulta.
     *
     * @param integer $start
     * @param integer|null $length
     * @return string
     */
    public static function limit( $start, $length = null )
    {
        if ( empty($start) )
            return '';

        if ( null === $length )
        {
            if ( is_array($start) )
            {
                $length = array_pop($start);
                $start = array_pop($start);

                return "LIMIT $start,$length";
            }

            return "LIMIT $start";
        }

        return "LIMIT $start,$length";
    }

    /**
     * Genera un ORDER BY como parte de sentencia una consulta.
     *
     * @param string|array $field
     * @return string
     */
    public static function order( $field )
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
     * Último ID insertado.
     *
     * @return integer
     */
    public static function inserted()
    {
        return self::$db->insert_id;
    }

    /**
     * Inserta un nuevo registro.
     *
     * @param string $table     nombre de la tabla
     * @param array $fields     campos a insertar
     * @return integer          id insertado
     */
    public static function insert( $table, $fields )
    {
        $fieldsNames  = array();
        $fieldsValues = array();

        foreach ( $fields as $name => $value )
        {
            $fieldsNames[]  = $name;
            $fieldsValues[] = self::quote($value);
        }

        $sql = "INSERT INTO $table ( " . implode(', ', $fieldsNames) . " )
                VALUES ( " . implode(', ', $fieldsValues) . " )";

        try {
            self::query($sql);
        } catch ( Exception $e ) {}

        return self::inserted();
    }

    /**
     * Cantidad de registros afectados en la última consulta.
     *
     * @return integer
     */
    public static function affected()
    {
        return self::$db->affected_rows;
    }

    /**
     * Actualiza registros.
     *
     * @param string $table         nombre de la tabla
     * @param array $fields         campos a actualizar
     * @param string|array $where   condiciones
     * @return integer              registros afectados
     */
    public static function update( $table, $fields, $where = null )
    {
        $fieldsSet = array();

        foreach ( $fields as $field => $value )
            $fieldsSet[] = "$field=" . self::quote($value);

        $where = self::where($where);

        $sql = "UPDATE $table
                SET " . implode(', ', $fieldsSet) . "
                WHERE $where";

        try {
            self::query($sql);
        } catch ( Exception $e ) {}

        return self::affected();
    }

    /**
     * Elimina registros.
     *
     * @param string $table         nombre de la tabla
     * @param string|array $where   condiciones
     * @return integer              registros afectados
     */
    public static function delete( $table, $where = null )
    {
        $where = self::where($where);

        $sql = "DELETE FROM $table
                WHERE $where";

        try {
            self::query($sql);
        } catch ( Exception $e ) {}

        return self::affected();
    }

    protected static function fetch( $sql, $type = self::FETCH_ASSOC )
    {
        self::$_queryCount++;

        if ( $stmt = self::$db->prepare($sql) )
        {
            $return = array();

            $stmt->execute();
            $stmt->store_result();

            $assoc = array();
            $array = array();

            $meta = $stmt->result_metadata();

            while ( $field = $meta->fetch_field() )
                $array[] = &$assoc[$field->name];

            call_user_func_array(array($stmt, 'bind_result'), $array);

            while ( $stmt->fetch() )
            {
                switch ( $type )
                {
                    case self::FETCH_ASSOC:
                        $return[] = array_map(create_function('$a', 'return "$a";'), $assoc);
                        break;

                    case self::FETCH_ARRAY:
                        $return[] = array_map(create_function('$a', 'return "$a";'), $array);
                        break;

                    case self::FETCH_PAIRS:
                        $return[$array[0]] = $array[1];
                        break;

                    case self::FETCH_FIRST:
                        $return[] = $array[0];
                        break;

                    case self::FETCH_ONE:
                        $return = $array[0];
                        break;

                    case self::FETCH_ROW:
                        $return = $assoc;
                        break;
                }

                if ( in_array($type, array(self::FETCH_ONE, self::FETCH_ROW)) )
                    break;
            }

            $meta->close();
            $stmt->close();

            return $return;
        }

        if ( self::$db->errno )
            throw new Exception(self::$db->error, self::$db->errno);

        return false;
    }

    /**
     * Obtiene registros en forma asociativa.
     *
     * @param string $sql
     * @return array
     */
    public static function fetchAssoc( $sql )
    {
        return self::fetch($sql, self::FETCH_ASSOC);
    }

    /**
     * Obtiene registros en una matriz numérica.
     *
     * @param string $sql
     * @return array
     */
    public static function fetchArray( $sql )
    {
        return self::fetch($sql, self::FETCH_ARRAY);
    }

    /**
     * Obtiene los dos primeros campos en una matriz asociativa.
     *
     * @param string $sql
     * @return array
     */
    public static function fetchPairs( $sql )
    {
        return self::fetch($sql, self::FETCH_PAIRS);
    }

    /**
     * Obtiene el primero campo en una matriz numérica.
     *
     * @param string $sql
     * @return array
     */
    public static function fetchFirst( $sql )
    {
        return self::fetch($sql, self::FETCH_FIRST);
    }

    /**
     * Obtiene el primero campo de la primera columna.
     *
     * @param string $sql
     * @return mixed
     */
    public static function fetchOne( $sql )
    {
        return self::fetch($sql, self::FETCH_ONE);
    }

    /**
     * Obtiene el primer registro.
     *
     * @param string $sql
     * @return array
     */
    public static function fetchRow( $sql )
    {
        return self::fetch($sql, self::FETCH_ROW);
    }

    /**
     * Obtiene una lista de tablas en la base de datos. Si el parámetro $table
     * es distinto de NULL, buscará si la tabla existe y devolverá un valor
     * booleano.
     *
     * @param string $table
     * @return array|boolean
     */
    public static function tables( $table = null )
    {
        static $tables = null;

        if ( null === $tables )
            $tables = Db::fetchFirst("SHOW TABLES");

        if ( null !== $table )
            return ( false !== array_search($table, $tables) );

        return $tables;
    }

    /**
     * Elimina una tabla.
     *
     * @param string $table
     * @return boolean
     */
    public static function drop( $table )
    {
        return Db::query("DROP TABLE $table");
    }

    /**
     * Obtiene una lista con los campos de una tabla.
     *
     * @param string $table
     * @return array
     */
    public static function fields( $table )
    {
        static $fields = array();

        if ( !( isset($fields[$table]) ) )
            $fields[$table] = Db::fetchAssoc("SHOW FIELDS FROM $table");

        return $fields[$table];
    }

    /**
     * Obtiene los índices de una tabla.
     *
     * @param string $table
     * @return array
     */
    public static function index( $table )
    {
        static $index = array();

        if ( !( isset($index[$table]) ) )
            $index[$table] = Db::fetchAssoc("SHOW INDEX FROM $table");

        return $index[$table];
    }

    /**
     * Devuelve la cantidad de registros en una tabla.
     *
     * @param string $table
     * @param string|array|null $where
     * @return integer
     */
    public static function count( $table, $where = null )
    {
        $where = self::where($where);

        return Db::fetchOne(
            "SELECT COUNT(*) FROM $table
             WHERE $where"
        );
    }

    /**
     * Vacia una tabla.
     *
     * @param string $table
     * @return boolean
     */
    public static function truncate( $table )
    {
        return Db::query("TRUNCATE $table");
    }

    public static function error()
    {
        return array(self::$db->connect_error, self::$db->connect_errno);
    }
}