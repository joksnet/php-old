<?php
function addslashes_recursive( $value )
{
    if ( is_array($value) )
    {
        foreach ( $value as $index => $val )
        {
            $value[$index] = addslashes_recursive($val);
        }

        return $value;
    }
    else
        return addslashes($value);
}

function stripslashes_recursive( $value )
{
    if ( is_array($value) )
    {
        foreach ( $value as $index => $val )
        {
            $value[$index] = stripslashes_recursive($val);
        }

        return $value;
    }
    else
        return stripslashes($value);
}

if ( !get_magic_quotes_gpc() )
{
    $_GET = addslashes_recursive($_GET);
    $_POST = addslashes_recursive($_POST);
    $_COOKIE = addslashes_recursive($_COOKIE);
    $_REQUEST = addslashes_recursive($_REQUEST);
}

require_once('config.php');

class Conn {
    var $id;

    function Conn()
    {
        global $db_config;

        $this->open(
            $db_config['hostname'],
            $db_config['username'],
            $db_config['password'],
            $db_config['database']
        );
    }

    function open( $hostname, $username, $password, $database )
    {
        $this->id = @mysql_connect($hostname, $username, $password);

        if( $this->id )
        {
            if( $database != '' )
            {
                $select_db = @mysql_select_db($database);

                if( !$select_db )
                {
                    @mysql_close($this->id);
                    $this->id = $select_db;
                }
            }
        }
        else
            die('<strong>Critical Error</strong>: Could not connect to database.');
    }

    function close()
    {
        @mysql_close($this->id);
    }
}

class Query {
    var $conection;
    var	$result;
    var	$row;
    var $numrows;
    var	$data;
    var $eof = false;
    var $elquery;

    function Query( $db )
    {
        $this->conection = $db;
    }

    function exec( $sql = '' )
    {
        if ( $sql == '' )
        {
            $this->result = 0;
            $this->numrows = 0;
            $this->row = -1;
        }

        $this->result = mysql_query($sql, $this->conection->id);

        if ( $this->result == false )
        {
            echo '<!-- Error SQL: ' . $sql . "\n" . ' -->';
        }

        $strSQL = strtolower( substr( $sql, 0, 3 ) );

        if ( $strSQL == 'sel' )
        {
            $this->numrows = mysql_num_rows($this->result);
            $this->row = 0;

            if ( $this->numrows > 0 )
                $this->go();
        }
        else
            $this->numrows = mysql_affected_rows($this->conection->id);

        $this->elquery = $sql;
    }

    function inic()
    {
        $this->row = 0;
        $this->go();
    }

    function nxt()
    {
        $this->row = ( $this->row < ( $this->numrows -1 ) ) ? ++$this->row : ( $this->numrows -1 );
        $this->go();
    }

    function prv()
    {
        $this->row = ( $this->row > 0 ) ? --$this->row : 0;
        $this->go();
    }

    function lst()
    {
        $this->row = $this->numrows -1;
        $this->go();
    }

    function goto( $lin )
    {
        if ( $lin >= 0 && $lin < $this->numrows )
        {
            $this->row = $lin;
            $this->go();
        }
    }

    function go()
    {
        mysql_data_seek($this->result, $this->row);

        $this->data = mysql_fetch_array($this->result, MYSQL_ASSOC);
        $this->eof = ( $this->row == $this->numrows -1 );
    }

    function free()
    {
        mysql_free_result($this->result);
    }
}

class Insert {
    var $db;
    var $table;
    var $col;
    var $_col;

    function Insert( $table, $db )
    {
        $this->db = $db;
        $this->table = $table;

        $q = new Query($this->db);
        $q->exec('SHOW COLUMNS FROM ' . $this->table);
        $q->inic();

        for ( $i = 0; $i < $q->numrows; $i++ )
        {
            $row = $q->data;
            $this->_col[$row['Field']] = $row['Field'];
            $q->nxt();
        }

        $q->free();
    }

    function update()
    {
        $inscols = 'INSERT INTO `' . $this->table . '` (';
        $insstr = '';

        foreach ( $this->col as $c => $cc )
        {
            if ( isset($this->_col[$c]) )
            {
                $inscols .= '`' . $c . '`,';

                if ( $cc == 'NOW()' )
                    $insstr .= $cc . ',';
                else
                    $insstr .= '\'' . $cc . '\',';
            }
        }

        $inscols = substr($inscols, 0, strlen($inscols) - 1) . ')';
        $insstr =  ' VALUES (' . substr($insstr, 0, strlen($insstr) - 1 ) . ')';

        $q = new Query($this->db);
        $q->exec($inscols . $insstr);
    }
}

class DoUpdate {
    var $db;
    var $table;
    var $col;
    var $_col;
    var $field;
    var $var;

    function DoUpdate( $table, $db, $field, $vars )
    {
        $this->db = $db;
        $this->table = $table;
        $this->vars = $vars;
        $this->field = $field;

        $q = new Query($this->db);
        $q->exec('SHOW COLUMNS FROM ' . $this->table);
        $q->inic();

        for ( $i = 0; $i < $q->numrows; $i++ )
        {
            $row = $q->data;
            $this->_col[$row['Field']] = $row['Field'];
            $q->nxt();
        }

        $q->free();
    }

    function update()
    {
        $inscols = 'UPDATE `' . $this->table . '` SET ';

        foreach ( $this->col as $c => $cc )
        {
            if ( isset($this->_col[$c]) )
            {
                if ( $cc == 'NOW()' )
                    $inscols .= '`' . $c . '` = ' . $cc . ',';
                else
                    $inscols .= '`' . $c . '` = \'' . $cc . '\',';
            }
        }

        $inscols = substr($inscols, 0, strlen($inscols) - 1);

        $q = new Query($this->db);
        $q->exec($inscols . ' WHERE ' . $this->field . ' = \'' . $this->vars . '\'');
    }
}

?>