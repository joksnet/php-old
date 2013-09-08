<?php

class Dev
{
    protected static $_instance = null;

    protected $_app = array();

    /**
     * @return Dev
     */
    public static function getInstance()
    {
        if ( is_null(self::$_instance) )
            self::$_instance = new self();

        return self::$_instance;
    }

    public function __construct()
    {
        if ( !( defined('LIB_PATH') || defined('BIN_PATH') ) )
            throw new Exception('Lib or Bin path\'s constants are not defined.');
    }

    public function __destruct()
    {
        foreach ( $this->_app as $app )
            if ( isset($app['db']) )
                $app['db']->close();
    }

    /**
     * @param string $connection
     * @return Dev
     */
    public function connect( $connection = null )
    {
        if ( is_null($connection) )
            $connection = CONNECTION;

        if ( preg_match('#(\w+):\/\/(\w+):(\w+)@(\w+)\/(\w+)#', $connection, $uri) )
            list($uri, $schema, $username, $password, $hostname, $database) = $uri;

        if ( !( file_exists(LIB_PATH . '/' . $schema . '.php') ) )
            throw new Exception('Schema doesn\'t exists.');

        include_once LIB_PATH . '/' . $schema . '.php';

        if ( !( isset($this->_app[$app]) ) )
            $this->_app[$app] = array();

        $className = ucfirst($schema);
        $classParam = array(
            'username' => $username,
            'password' => $password,
            'hostname' => $hostname,
            'database' => $database
        );

        if ( !( isset($this->_app[$app]['db']) ) )
            $this->_app[$app]['db'] = new $className();

        if ( $this->_app[$app]['db']->ready() )
        {
            $this->_app[$app]['db']->open($classParam);
            $this->_app[$app]['db']->createDatabase();
        }

        return $this;
    }

    /**
     * @param string $app
     * @return Dev
     */
    public function add( $app )
    {
        $schema = '';

        if ( !( file_exists(BIN_PATH . '/' . $app . '.php') ) )
            throw new Exception('Application doesn\'t exists.');

        include_once BIN_PATH . '/' . $app . '.php';

        if ( !( defined('CONNECTION') ) )
            throw new Exception('Connection constant is not defined.');

        $this->connect();
        $this->sql();

        return $this;
    }

    public function sql()
    {
        $sql = array();

        if ( isset($this->_app[$app]['db']) )
        {
            $db = $this->_app[$app]['db'];
            $tables = explode(':', TABLES);

            if ( !( isset($this->_app[$app]['tables']) ) )
                $this->_app[$app]['tables'] = array();

            foreach ( $tables as $table )
            {
                if ( class_exists($table) )
                {
                    $className = $this->_app[$app]['tables'][$table] = new $table();

                }
            }
        }

        echo implode(';', $sql);
    }

    /**
     * @return Dev
     */
    public function dispatch()
    {

    }
}

class Models
{
    public static function Char() { return new FieldChar(); }
    public static function Integer() { return new FieldInteger(); }
    public static function DateTime() { return new FieldDateTime(); }
    public static function ForeignKey( $table ) { return new FieldForeignKey($table); }
}

class Model
{

}

class Field
{
    protected $comment = '';

    /**
     * @param string $comment
     * @return Field
     */
    public function comment( $comment ) { $this->comment = $comment; return $this; }
}

class FieldChar extends Field
{
    protected $length = 0;

    /**
     * @param int $length
     * @return FieldChar
     */
    public function length( $length )
    {
        if ( is_numeric($length) )
            $this->length = $length;

        return $this;
    }
}

class FieldInteger extends Field
{

}

class FieldDateTime extends Field
{

}

class FieldForeignKey extends Field
{
    protected $table;

    public function __construct( $table )
    {
        $this->table = $table;
    }
}