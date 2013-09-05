<?php

include_once 'Wax/Db.php';
include_once 'Wax/Db/Query/Exception.php';

class Wax_Db_Query_Select
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
                        $columns[] = Wax_Db::quoteIdentifierAs($column, $alias);
                    else
                        $columns[] = Wax_Db::quoteIdentifierAs(array($tableName, $column), $alias);
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
                    $tmp .= Wax_Db::quoteIdentifierAs($table['tableName'], $correlationName);
                else
                {
                    if ( !( empty($table['joinType']) ) )
                        $tmp .= ' ' . strtoupper($table['joinType']) . ' JOIN ';

                    $tmp .= Wax_Db::quoteIdentifierAs($table['tableName'], $correlationName);

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
     * @return Wax_Db_Query_Select
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
     * @return Wax_Db_Query_Select
     */
    public function distinct( $flag = true )
    {
        if ( is_bool($flag) )
            $this->_distinct = $flag;
        return $this;
    }

    /**
     * @return Wax_Db_Query_Select
     */
    public function from( $name, $cols = '*' )
    {
        return $this->joinInner($name, null, $cols);
    }

    /**
     * @return Wax_Db_Query_Select
     */
    public function joinInner( $name, $condition, $cols = '*' )
    {
        return $this->_join('inner', $name, $condition, $cols);
    }

    /**
     * @return Wax_Db_Query_Select
     */
    public function joinLeft( $name, $condition, $cols = '*' )
    {
        return $this->_join('left', $name, $condition, $cols);
    }

    /**
     * @return Wax_Db_Query_Select
     */
    public function joinRight( $name, $condition, $cols = '*' )
    {
        return $this->_join('right', $name, $condition, $cols);
    }

    /**
     * @return Wax_Db_Query_Select
     */
    public function where( $condition )
    {
        $num = func_num_args();

        if ( $num == 2 )
        {
            $value = func_get_arg(1);

            if ( is_array($value) || strpos($condition, Wax_Db::PLACEHOLDER) )
                $condition = Db::quoteInto($condition, $value);
            else
                $condition = "$condition = " . Wax_Db::quote($value);
        }
        elseif ( $num > 1 )
        {
            $bind = func_get_args(); array_shift($bind);
            $condition = Wax_Db::quoteInto($condition, $bind);
        }

        if ( sizeof($this->_where) != 0 )
            $this->_where[] = " AND ( $condition )";
        else
            $this->_where[] = " ( $condition )";

        return $this;
    }

    /**
     * @return Wax_Db_Query_Select
     */
    public function orWhere( $condition )
    {
        $num = func_num_args();

        if ( $num == 2 )
        {
            $value = func_get_arg(1);

            if ( is_array($value) || strpos($condition, Wax_Db::PLACEHOLDER) )
                $condition = Wax_Db::quoteInto($condition, $value);
            else
                $condition = "$condition = " . Wax_Db::quote($value);
        }
        elseif ( $num > 1 )
        {
            $bind = func_get_args(); array_shift($bind);
            $condition = Wax_Db::quoteInto($condition, $bind);
        }

        if ( sizeof($this->_where) != 0 )
            $this->_where[] = " OR ( $condition )";
        else
            $this->_where[] = " ( $condition )";

        return $this;
    }

    /**
     * @return Wax_Db_Query_Select
     */
    public function like( $field, $value, $caseSensitive = true )
    {
        $value = str_replace('*', '%', $value);

        if ( $caseSensitive )
        {
            $value = strtolower($value);
            $field = sprintf('LCASE(%s)', $field);
        }

        $condition = Wax_Db::quoteInto("$field LIKE ?"
            , $value
        );
        $this->where($condition);

        return $this;
    }

    /**
     * @return Wax_Db_Query_Select
     */
    public function orLike( $field, $value, $caseSensitive = true )
    {
        $value = str_replace('*', '%', $value);

        if ( $caseSensitive )
        {
            $value = strtolower($value);
            $field = sprintf('LCASE(%s)', $field);
        }

        $condition = Wax_Db::quoteInto("$field LIKE ?"
            , $value
        );
        $this->orWhere($condition);

        return $this;
    }

    /**
     * @return Wax_Db_Query_Select
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

                    $this->_group[] = Wax_Db::quoteIdentifierAs($v);
                }
            }
            else
                $this->_group[] = Wax_Db::quoteIdentifierAs($value);
        }

        return $this;
    }

    /**
     * @return Wax_Db_Query_Select
     */
    public function having( $condition )
    {
        $num = func_num_args();

        if ( $num == 2 )
        {
            $value = func_get_arg(1);

            if ( is_array($value) || strpos($condition, Wax_Db::PLACEHOLDER) )
                $condition = Wax_Db::quoteInto($condition, $value);
            else
                $condition = "$condition = " . Wax_Db::quote($value);
        }
        elseif ( $num > 1 )
        {
            $bind = func_get_args(); array_shift($bind);
            $condition = Wax_Db::quoteInto($condition, $bind);
        }

        if ( sizeof($this->_having) != 0 )
            $this->_having[] = " AND ( $condition )";
        else
            $this->_having[] = " ( $condition )";

        return $this;
    }

    /**
     * @return Wax_Db_Query_Select
     */
    public function orHaving( $condition )
    {
        $num = func_num_args();

        if ( $num == 2 )
        {
            $value = func_get_arg(1);

            if ( is_array($value) || strpos($condition, Wax_Db::PLACEHOLDER) )
                $condition = Wax_Db::quoteInto($condition, $value);
            else
                $condition = "$condition = " . Wax_Db::quote($value);
        }
        elseif ( $num > 1 )
        {
            $bind = func_get_args(); array_shift($bind);
            $condition = Wax_Db::quoteInto($condition, $bind);
        }

        if ( sizeof($this->_having) != 0 )
            $this->_having[] = " OR ( $condition )";
        else
            $this->_having[] = " ( $condition )";

        return $this;
    }

    /**
     * @return Wax_Db_Query_Select
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

            $this->_order[] = Wax_Db::quoteIdentifierAs($value) . " $direction";
        }

        return $this;
    }

    /**
     * @return Wax_Db_Query
     */
    public function query()
    {
        return Wax_Db::query($this->__toString());
    }
}