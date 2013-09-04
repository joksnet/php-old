<?php

class Configuration
{
    protected static $instance;

    protected $config;

    protected $idioma;
    protected $content = array();

    public static function getInstance()
    {
        if ( !( self::$instance instanceof Configuration ) )
            self::$instance = new Configuration();

        return self::$instance;
    }

    public function __construct()
    {
        $this->config = parse_ini_file('application.ini');

        $results = Db::fetchAssoc(Db::select(array('idioma', 'nombre', 'valor'), 'configuracion', null, 'idioma'));
        $content = array();

        if ( $results )
        {
            $length = sizeof($results);

            $resultN = 0;
            $result = $results[$resultN];

            while ( $resultN < $length )
            {
                $idioma = $result['idioma'];
                $pairs = array();

                while ( $resultN < $length && $result['idioma'] == $idioma )
                {
                    $pairs[$result['nombre']] = $result['valor'];

                    $resultN++;
                    $result = isset($results[$resultN]) ? $results[$resultN] : array();
                }

                $content[$idioma] = $pairs;
            }
        }

        $this->content = $content;
    }

    public function __get( $name )
    {
        if ( strpos($name, '.') === false )
        {
            if ( isset($this->config[$name]) )
                return $this->config[$name];
        }
        else
        {
            list($idioma, $name) = explode('.', $name, 2);

            if ( isset($this->content[$idioma]) )
                if ( isset($this->content[$idioma][$name]) )
                    return $this->content[$idioma][$name];
        }

        return null;
    }

    public function __isset( $name )
    {
        return !( null === $this->__get($name) );
    }

    public function setIdioma( $idioma )
    {
        $this->idioma = $idioma;
    }

    public function getContent( $name, $idioma = null )
    {
        if ( null === $idioma && !( $this->idioma ) )
            throw new Exception('Language not set.');

        if ( null === $idioma )
            $idioma = $this->idioma;

        return $this->__get("$idioma.$name");
    }
}