<?php

class Mysql
{
    const PLACEHOLDER = '?';
    const IDENTIFIER = '"';

    private static $_db = null;
    private static $_dbName = '';

    public static function open( $config )
    {
        self::$_db = @mysql_connect(
            $config['hostname'],
            $config['username'],
            $config['password']
        );

        if ( self::$_db )
        {
            if ( !( @mysql_select_db($config['database']) ) )
                self::$_dbName = $config['database'];
        }
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
                $value[$key] = Mysql::quote($val);

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
                           . Mysql::quote($val)
                           . substr(strstr($value, self::PLACEHOLDER), 1);
            }

            return $value;
        }
        elseif ( is_array($bind) )
        {
            foreach ( $bind as $key => $val )
            {
                if ( is_string($key) )
                    $value = str_replace($key, Mysql::quote($val), $value);
                elseif ( strpos($value, self::PLACEHOLDER) )
                    $value = substr($value, 0, strpos($value, self::PLACEHOLDER))
                           . Mysql::quote($val)
                           . substr(strstr($value, self::PLACEHOLDER), 1);
            }

            return $value;
        }
        else
            return str_replace(self::PLACEHOLDER, Mysql::quote($bind), $value);
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
     * @return Mysql_Query
     */
    public static function fields( $table )
    {
        return self::query("SHOW FIELDS FROM $table");
    }

    /**
     * @return Mysql_Query
     */
    public static function query( $sql )
    {
        if ( $sql instanceof Mysql_Query_Select )
        	$sql = $sql->__toString();
        if ( $sql instanceof Mysql_Query_Delete )
        	$sql = $sql->__toString();
        if ( $sql instanceof Mysql_Query_Update )
        	$sql = $sql->__toString();
        if ( $sql instanceof Mysql_Query_Insert )
        	$sql = $sql->__toString();

        return new Mysql_Query($sql);
    }

    /**
     * @return Mysql_Query_Delete
     */
    public static function delete( $table = '' )
    {
        return new Mysql_Query_Delete($table);
    }

    /**
     * @return Mysql_Query_Update
     */
    public static function update( $table = '' )
    {
        return new Mysql_Query_Update($table);
    }

    /**
     * @return Mysql_Query_Insert
     */
    public static function insert( $table = '' )
    {
        return new Mysql_Query_Insert($table);
    }

    /**
     * @return Mysql_Query_Select
     */
    public static function select( $table = '' )
    {
        return new Mysql_Query_Select($table);
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

    public static function tableExists( $table )
    {
        if ( self::$_db )
        {
            $query = self::query('SHOW TABLE STATUS WHERE Name = "' . $table . '"');
            $queryCount = $query->numRows();
            $query->free();

            return ( $queryCount > 0 );
        }
        else
            return false;
    }

    public static function databaseExists( $database )
    {
        if ( self::$_db )
        {
            $query = self::query('SHOW DATABASES WHERE Database = "' . $database . '"');
            $queryCount = $query->numRows();
            $query->free();

            return ( $queryCount > 0 );
        }
        else
            return false;
    }

    public static function createDatabase( $return = false )
    {
        if ( self::$_db && strlen(self::$_dbName) > 0 )
        {
            if ( !( self::databaseExists(self::$_dbName) ) )
            {
                $sql = 'CREATE DATABASE ' . self::$_dbName;

                if ( $return )
                    return $sql;

                self::query('CREATE DATABASE ' . self::$_dbName)->free();
            }
        }

        if ( $return )
            return '';
        else
            return true;
    }
}

class Mysql_Table
{
    const TYPE_AUTOMATIC    = 0;
    const TYPE_MYISAM       = 1;
    const TYPE_MEMORY       = 2;
    const TYPE_INNODB       = 3;
    const TYPE_ARCHIVE      = 4;
    const TYPE_CSV          = 5;
    const TYPE_NDBCLUSTER   = 6;
    const TYPE_FEDERATED    = 7;
    const TYPE_MRGMYISAM    = 8;

    public $_table = '';
    public $_fields = array();

    public $_comment = '';
    public $_type = 0;
    public $_types = array(
        self::TYPE_MYISAM       => 'MyISAM',
        self::TYPE_MEMORY       => 'MEMORY',
        self::TYPE_INNODB       => 'InnoDB',
        self::TYPE_CSV          => 'CSV',
        self::TYPE_NDBCLUSTER   => 'ndbcluster',
        self::TYPE_MRGMYISAM    => 'MRG_MYISAM'
    );

    public function __construct( $table = '' )
    {
        $this->from($table);
    }

    public function __toString()
    {
        $fields = '';
        $comment = ( strlen($this->_comment) > 0 ) ? sprintf(' COMMENT = "%s"', $this->_comment) : '';
        $type = ( $this->_type > 0 ) ? sprintf(' TYPE = %s', $this->_types[$this->_type]) : '';

        return sprintf('CREATE TABLE %s (%s)%s%s',
                   $this->_table,
                   $fields,
                   $comment,
                   $type
               );
    }

    /**
     * @return Mysql_Table
     */
    public function from( $table )
    {
        if ( strlen($table) > 0 )
            $this->_table = $table;
        return $this;
    }

    /**
     * @return Mysql_Table
     */
    public function comment( $comment )
    {
        if ( strlen($comment) > 0 )
            $this->_comment = $comment;
        return $this;
    }

    /**
     * @return Mysql_Table
     */
    public function type( $type )
    {
        if ( isset($this->_types[$type]) || $type == 0 )
            $this->_type = $type;
        return $this;
    }

    /**
     * @return Mysql_Query
     */
    public function query()
    {
        return Mysql::query($this->__toString());
    }
}

class Mysql_Query
{
    private $_sql = '';

    public function __construct( $sql )
    {
        $this->_sql = $sql;
        $this->__prepare();

        if ( is_string($this->_sql) && strlen($this->_sql) > 0 )
        {
            $this->_result = @mysql_query($this->_sql);

            if ( !( $this->_result ) )
            {
                $error = mysql_error();

                echo $error;
                echo $sql;

                exit();
            }
        }
        else
            return false;
    }

    private function __prepare()
    {
        $this->_sql = ltrim($this->_sql);
    }

    public function free()
    {
        if ( $this->_result )
            return @mysql_free_result($this->_result);
        else
            return false;
    }

    public function getInsertId()
    {
        if ( $this->_result )
            return Mysql::getInsertId();
        else
            return false;
    }

    public function getAffectedRows()
    {
        if ( $this->_result )
            return Mysql::getAffectedRows();
        else
            return false;
    }

    public function fetchAll()
    {
        if ( $this->_result )
        {
            $data = array();

            while ( $row = mysql_fetch_array($this->_result, MYSQL_ASSOC) )
                $data[] = $row;

            return $data;
        }
        else
            return false;
    }

    public function fetchAssoc()
    {
        if ( $this->_result )
        {
            $data = array();

            while ( $row = mysql_fetch_assoc($this->_result) )
            {
                $tmp = array_values(array_slice($row, 0, 1));
                $data[$tmp[0]] = $row;
            }

            return $data;
        }
        else
            return false;
    }

    public function fetchCol()
    {
        if ( $this->_result )
        {
            $data = array();

            while ( $row = mysql_fetch_array($this->_result, MYSQL_NUM) )
                $data[] = $row[0];

            return $data;
        }
        else
            return false;
    }

    public function fetchPairs()
    {
        if ( $this->_result )
        {
            $data = array();

            while ( $row = mysql_fetch_array($this->_result, MYSQL_NUM) )
                $data[$row[0]] = $row[1];

            return $data;
        }
        else
            return false;
    }

    public function fetchOne()
    {
        if ( $this->_result )
        {
            $row = mysql_fetch_array($this->_result, MYSQL_NUM);
            return $row[0];
        }
        else
            return false;
    }

    public function fetchRow()
    {
        if ( $this->_result )
            return mysql_fetch_array($this->_result, MYSQL_ASSOC);
        else
            return false;
    }

    public function numRows()
    {
        if ( $this->_result )
            return @mysql_num_rows($this->_result);
        else
            return false;
    }

    public function dump()
    {
        echo $this->_sql;
    }

    public function __toString()
    {
        return $this->_sql;
    }
}

class Mysql_Query_Delete
{
    public $_table = '';
    public $_where = array();

    public function __construct( $table = '' )
    {
        $this->from($table);
    }

    public function __toString()
    {
        return 'DELETE FROM ' . $this->_table
             . ( ( sizeof($this->_where) > 0 ) ? ' WHERE' . implode('', $this->_where) : '' )
             . ';';
    }

    /**
     * @return Mysql_Query_Delete
     */
    public function from( $table )
    {
        if ( strlen($table) > 0 )
            $this->_table = $table;
        return $this;
    }

    /**
     * @return Mysql_Query_Delete
     */
    public function where( $condition )
    {
        $num = func_num_args();

        if ( $num == 2 )
        {
            $value = func_get_arg(1);

            if ( is_array($value) || strpos($condition, Mysql::PLACEHOLDER) )
                $condition = Mysql::quoteInto($condition, $value);
            else
                $condition = "$condition = " . Mysql::quote($value);
        }
        elseif ( $num > 1 )
        {
            $bind = func_get_args(); array_shift($bind);
            $condition = Mysql::quoteInto($condition, $bind);
        }

        if ( sizeof($this->_where) != 0 )
            $this->_where[] = " AND ( $condition )";
        else
            $this->_where[] = " ( $condition )";

        return $this;
    }

    /**
     * @return Mysql_Query_Delete
     */
    public function orWhere( $condition )
    {
        $num = func_num_args();

        if ( $num == 2 )
        {
            $value = func_get_arg(1);

            if ( is_array($value) || strpos($condition, Mysql::PLACEHOLDER) )
                $condition = Mysql::quoteInto($condition, $value);
            else
                $condition = "$condition = " . Mysql::quote($value);
        }
        elseif ( $num > 1 )
        {
            $bind = func_get_args(); array_shift($bind);
            $condition = Mysql::quoteInto($condition, $bind);
        }

        if ( sizeof($this->_where) != 0 )
            $this->_where[] = " OR ( $condition )";
        else
            $this->_where[] = " ( $condition )";

        return $this;
    }

    /**
     * @return Mysql_Query
     */
    public function query()
    {
        return Mysql::query($this->__toString());
    }
}

class Mysql_Query_Update
{
    const MD5 = 1;

    public $_table = '';
    public $_fields = array();
    public $_where = array();

    public function __construct( $table = '' )
    {
        $this->from($table);
    }

    public function __toString()
    {
        return 'UPDATE ' . $this->_table
             . ' SET ' . implode(', ', $this->_fields)
             . ( ( sizeof($this->_where) > 0 ) ? ' WHERE' . implode('', $this->_where) : '' )
             . ';';
    }

    /**
     * @return Mysql_Query_Update
     */
    public function from( $table )
    {
        if ( strlen($table) > 0 )
            $this->_table = $table;
        return $this;
    }

    /**
     * @return Mysql_Query_Update
     */
    public function where( $condition )
    {
        $num = func_num_args();

        if ( $num == 2 )
        {
            $value = func_get_arg(1);

            if ( is_array($value) || strpos($condition, Mysql::PLACEHOLDER) )
                $condition = Mysql::quoteInto($condition, $value);
            else
                $condition = "$condition = " . Mysql::quote($value);
        }
        elseif ( $num > 1 )
        {
            $bind = func_get_args(); array_shift($bind);
            $condition = Mysql::quoteInto($condition, $bind);
        }

        if ( sizeof($this->_where) != 0 )
            $this->_where[] = " AND ( $condition )";
        else
            $this->_where[] = " ( $condition )";

        return $this;
    }

    /**
     * @return Mysql_Query_Update
     */
    public function orWhere( $condition )
    {
        $num = func_num_args();

        if ( $num == 2 )
        {
            $value = func_get_arg(1);

            if ( is_array($value) || strpos($condition, Mysql::PLACEHOLDER) )
                $condition = Mysql::quoteInto($condition, $value);
            else
                $condition = "$condition = " . Mysql::quote($value);
        }
        elseif ( $num > 1 )
        {
            $bind = func_get_args(); array_shift($bind);
            $condition = Mysql::quoteInto($condition, $bind);
        }

        if ( sizeof($this->_where) != 0 )
            $this->_where[] = " OR ( $condition )";
        else
            $this->_where[] = " ( $condition )";

        return $this;
    }

    /**
     * @return Mysql_Query_Update
     */
    public function set( $field, $value, $fn = null )
    {
        switch ( $fn )
        {
            case self::MD5:
                $this->_fields[] = "$field = MD5(" . Mysql::quote($value) . ")";
                break;
            default:
                $this->_fields[] = "$field = " . Mysql::quote($value);
                break;
        }

        return $this;
    }

    /**
     * @return Mysql_Query
     */
    public function query()
    {
        return Mysql::query($this->__toString());
    }
}

class Mysql_Query_Insert
{
    const MD5 = 1;

    public $_table = '';
    public $_fields = array();
    public $_values = array();

    public function __construct( $table = '' )
    {
        $this->from($table);
    }

    public function __toString()
    {
        return 'INSERT INTO ' . $this->_table
             . ' ( ' . implode(', ', $this->_fields) . ' )'
             . ' VALUES ( ' . implode(', ', $this->_values) . ' )'
             . ';';
    }

    /**
     * @return Mysql_Query_Insert
     */
    public function from( $table )
    {
        if ( strlen($table) > 0 )
            $this->_table = $table;
        return $this;
    }

    /**
     * @return Mysql_Query_Insert
     */
    public function set( $field, $value, $fn = null )
    {
        $this->_fields[] = $field;

        switch ( $fn )
        {
            case self::MD5:
                $this->_values[] = "MD5(" . Mysql::quote($value) . ")";
                break;
            default:
                $this->_values[] = Mysql::quote($value);
                break;
        }

        return $this;
    }

    /**
     * @return Mysql_Query
     */
    public function query()
    {
        return Mysql::query($this->__toString());
    }
}

class Mysql_Query_Select
{
    private $_distinct = false;
    private $_columns = array();
    private $_from = array();
    private $_where = array();
    private $_group = array();
    private $_having = array();
    private $_order = array();

    public function __construct() {}

    public function __toString()
    {
        $sql = 'SELECT';

        if ( $this->_distinct )
            $sql .= ' DISTINCT';

        if ( sizeof($this->_columns) > 0 )
        {
            $columns = array();

            foreach ( $this->_columns as $tableName => $columnsList )
            {
                foreach ( $columnsList as $alias => $column )
                {
                    if ( !( is_string($alias) ) || $column == '*' )
                        $alias = null;

                    if ( empty($tableName) )
                        $columns[] = Mysql::quoteIdentifierAs($column, $alias);
                    else
                        $columns[] = Mysql::quoteIdentifierAs(array($tableName, $column), $alias);
                }
            }

            $sql .= ' ' . implode(', ', $columns);
        }

        if ( sizeof($this->_from) > 0 )
        {
            $from = array();

            foreach ( $this->_from as $correlationName => $table )
            {
                $tmp = '';

                if ( empty($from) )
                    $tmp .= Mysql::quoteIdentifierAs($table['tableName'], $correlationName);
                else
                {
                    if ( !( empty($table['joinType']) ) )
                        $tmp .= ' ' . strtoupper($table['joinType']) . ' JOIN ';

                    $tmp .= Mysql::quoteIdentifierAs($table['tableName'], $correlationName);

                    if ( !( empty($table['joinCondition']) ) )
                        $tmp .= ' ON ( ' . $table['joinCondition'] . ' )';
                }

                $from[] = $tmp;
            }

            if ( !( empty($from) ) )
                $sql .= ' FROM ' . implode('', $from);

            if ( sizeof($this->_where) > 0 )
            {
                $sql .= ' WHERE';
                $sql .= implode('', $this->_where);
            }

            if ( sizeof($this->_group) > 0 )
            {
                $sql .= ' GROUP BY ';
                $sql .= implode(', ', $this->_group);
            }

            if ( sizeof($this->_having) > 0 )
            {
                $sql .= ' HAVING';
                $sql .= implode('', $this->_having);
            }
        }

        if ( sizeof($this->_order) > 0 )
        {
            $sql .= ' ORDER BY ';
            $sql .= implode(', ', $this->_order);
        }

        /**
         * LIMIT
         */

        return $sql . ';';
    }

    /**
     * @return Mysql_Query_Select
     */
    private function _join( $type, $name, $condition, $cols )
    {
        if ( empty($name) )
        {
            $correlationName = $tableName = '';
        }
        elseif ( is_array($name) )
        {
            foreach ( $name as $_correlationName => $_tableName )
            {
                if ( is_string($_correlationName) )
                {
                    $tableName = $_tableName;
                    $correlationName = $_correlationName;
                }
                else
                {
                    $tableName = $name;
                    $correlationName = $this->_uniqueCorrelation($tableName);
                }

                break;
            }
        }
        elseif ( preg_match('/^(.+)\s+AS\s+(.+)$/i', $name, $m) )
        {
            $tableName = $m[1];
            $correlationName = $m[2];
        }
        else
        {
            $tableName = $name;
            $correlationName = $this->_uniqueCorrelation($tableName);
        }

        if ( !( empty($correlationName) ) )
        {
            if ( array_key_exists($correlationName, $this->_from) )
            {
                die("You cannot define a correlation name '$correlationName' more than once");
            }

            $this->_from[$correlationName] = array(
                'joinType' => $type,
                'tableName' => $tableName,
                'joinCondition' => $condition
            );
        }

        $this->_tableCols($correlationName, $cols);

        return $this;
    }

    private function _uniqueCorrelation( $name )
    {
        if ( is_array($name) )
            $c = end($name);
        else
        {
            $dot = strrpos($name,'.');
            $c = ($dot === false) ? $name : substr($name, $dot+1);
        }

        for ( $i = 2; array_key_exists($c, $this->_from); ++$i)
            $c = $name . '_' . (string) $i;

        return $c;
    }

    private function _tableCols($correlationName, $cols)
    {
        if ( !( is_array($cols) ) )
            $cols = array($cols);

        if ( $correlationName == null )
            $correlationName = '';

        foreach ( $cols as $alias => $col )
        {
            $currentCorrelationName = $correlationName;

            if ( is_string($col) )
            {
                if ( preg_match('/^(.+)\s+AS\s+(.+)$/i', $col, $m) )
                {
                    $col = $m[1];
                    $alias = $m[2];
                }

                if ( preg_match('/(.+)\.(.+)/', $col, $m) )
                {
                    $currentCorrelationName = $m[1];
                    $col = $m[2];
                }
            }

            if ( is_string($alias) )
                $this->_columns[$currentCorrelationName][$alias] = $col;
            else
                $this->_columns[$currentCorrelationName][] = $col;
        }
    }

    /**
     * @return Mysql_Query_Select
     */
    public function distinct( $flag = true )
    {
        if ( is_bool($flag) )
            $this->_distinct = $flag;
        return $this;
    }

    /**
     * @return Mysql_Query_Select
     */
    public function from( $name, $cols = '*' )
    {
        return $this->joinInner($name, null, $cols);
    }

    /**
     * @return Mysql_Query_Select
     */
    public function joinInner( $name, $condition, $cols = '*' )
    {
        return $this->_join('inner', $name, $condition, $cols);
    }

    /**
     * @return Mysql_Query_Select
     */
    public function joinLeft( $name, $condition, $cols = '*' )
    {
        return $this->_join('left', $name, $condition, $cols);
    }

    /**
     * @return Mysql_Query_Select
     */
    public function joinRight( $name, $condition, $cols = '*' )
    {
        return $this->_join('right', $name, $condition, $cols);
    }

    /**
     * @return Mysql_Query_Select
     */
    public function where( $condition )
    {
        $num = func_num_args();

        if ( $num == 2 )
        {
            $value = func_get_arg(1);

            if ( is_array($value) || strpos($condition, Mysql::PLACEHOLDER) )
                $condition = Mysql::quoteInto($condition, $value);
            else
                $condition = "$condition = " . Mysql::quote($value);
        }
        elseif ( $num > 1 )
        {
            $bind = func_get_args(); array_shift($bind);
            $condition = Mysql::quoteInto($condition, $bind);
        }

        if ( sizeof($this->_where) != 0 )
            $this->_where[] = " AND ( $condition )";
        else
            $this->_where[] = " ( $condition )";

        return $this;
    }

    /**
     * @return Mysql_Query_Select
     */
    public function orWhere( $condition )
    {
        $num = func_num_args();

        if ( $num == 2 )
        {
            $value = func_get_arg(1);

            if ( is_array($value) || strpos($condition, Mysql::PLACEHOLDER) )
                $condition = Mysql::quoteInto($condition, $value);
            else
                $condition = "$condition = " . Mysql::quote($value);
        }
        elseif ( $num > 1 )
        {
            $bind = func_get_args(); array_shift($bind);
            $condition = Mysql::quoteInto($condition, $bind);
        }

        if ( sizeof($this->_where) != 0 )
            $this->_where[] = " OR ( $condition )";
        else
            $this->_where[] = " ( $condition )";

        return $this;
    }

    /**
     * @return Mysql_Query_Select
     */
    public function like( $field, $value, $caseSensitive = true )
    {
        $value = str_replace('*', '%', $value);

        if ( $caseSensitive )
        {
            $value = strtolower($value);
            $field = sprintf('LCASE(%s)', $field);
        }

        $condition = Mysql::quoteInto("$field LIKE ?"
            , $value
        );
        $this->where($condition);

        return $this;
    }

    /**
     * @return Mysql_Query_Select
     */
    public function orLike( $field, $value, $caseSensitive = true )
    {
        $value = str_replace('*', '%', $value);

        if ( $caseSensitive )
        {
            $value = strtolower($value);
            $field = sprintf('LCASE(%s)', $field);
        }

        $condition = Mysql::quoteInto("$field LIKE ?"
            , $value
        );
        $this->orWhere($condition);

        return $this;
    }

    /**
     * @return Mysql_Query_Select
     */
    public function group( $spec )
    {
        $num = func_num_args();

        for ( $i = 0; $i < $num; $i++ )
        {
            $value = func_get_arg($i);

            if ( empty($value) )
                continue;

            if ( is_array($value) )
            {
                foreach ( $value as $k => $v )
                {
                    if ( !( empty($k) ) )
                        $v = array($k => $v);

                    $this->_group[] = Mysql::quoteIdentifierAs($v);
                }
            }
            else
                $this->_group[] = Mysql::quoteIdentifierAs($value);
        }

        return $this;
    }

    /**
     * @return Mysql_Query_Select
     */
    public function having( $condition )
    {
        $num = func_num_args();

        if ( $num == 2 )
        {
            $value = func_get_arg(1);

            if ( is_array($value) || strpos($condition, Mysql::PLACEHOLDER) )
                $condition = Mysql::quoteInto($condition, $value);
            else
                $condition = "$condition = " . Mysql::quote($value);
        }
        elseif ( $num > 1 )
        {
            $bind = func_get_args(); array_shift($bind);
            $condition = Mysql::quoteInto($condition, $bind);
        }

        if ( sizeof($this->_having) != 0 )
            $this->_having[] = " AND ( $condition )";
        else
            $this->_having[] = " ( $condition )";

        return $this;
    }

    /**
     * @return Mysql_Query_Select
     */
    public function orHaving( $condition )
    {
        $num = func_num_args();

        if ( $num == 2 )
        {
            $value = func_get_arg(1);

            if ( is_array($value) || strpos($condition, Mysql::PLACEHOLDER) )
                $condition = Mysql::quoteInto($condition, $value);
            else
                $condition = "$condition = " . Mysql::quote($value);
        }
        elseif ( $num > 1 )
        {
            $bind = func_get_args(); array_shift($bind);
            $condition = Mysql::quoteInto($condition, $bind);
        }

        if ( sizeof($this->_having) != 0 )
            $this->_having[] = " OR ( $condition )";
        else
            $this->_having[] = " ( $condition )";

        return $this;
    }

    /**
     * @return Mysql_Query_Select
     */
    public function order( $spec, $default = 'ASC' )
    {
        if ( !( is_array($spec) ) )
            $spec = array($spec);

        foreach ( $spec as $key => $value )
        {
            if ( empty($value) )
                continue;

            $direction = $default;

            if ( preg_match('/(.*)\s+(ASC|DESC)\s*$/i', $value, $matches) )
            {
                $value = trim($matches[1]);
                $direction = $matches[2];
            }

            if ( !( empty($key) ) )
                $value = array($key => $value);

            $this->_order[] = Mysql::quoteIdentifierAs($value) . " $direction";
        }

        return $this;
    }

    /**
     * @return Mysql_Query
     */
    public function query()
    {
        return Mysql::query($this->__toString());
    }
}

?>