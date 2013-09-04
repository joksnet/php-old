<?php

class Session
{
    public static $prefix = '';

    /**
     * Inicializa el uso de la sesión.
     */
    public static function init()
    {
        session_start();
    }

    /**
     * Elimina una sesión.
     */
    public static function destroy()
    {
        session_destroy();
    }

    /**
     * Obtiene un valor de la sesión.
     *
     * @param string $name
     * @return mixed
     */
    public static function get( $name )
    {
        if ( !( self::has($name) ) )
            return null;

        $data = $_SESSION[self::$prefix . $name];
        $data = self::decode($data);

        return $data;
    }

    /**
     * Asigna una valor a la sesión.
     *
     * @param string $name
     * @param mixed $value
     */
    public static function set( $name, $value )
    {
        $value = self::encode($value);

        if ( self::has($name) )
            self::del($name);

        $_SESSION[self::$prefix . $name] = $value;
    }

    /**
     * Verifica si existe el valor en la sesión.
     *
     * @param string $name
     * @return boolean
     */
    public static function has( $name )
    {
        return ( isset($_SESSION[self::$prefix . $name]) );
    }

    /**
     * Elimina un valor de la sesión.
     *
     * @param string $name
     */
    public static function del( $name )
    {
        unset($_SESSION[self::$prefix . $name]);
    }

    /**
     * Devuelve el ID de la sesión.
     *
     * @return string
     */
    public static function id()
    {
        return session_id();
    }

    /**
     * Devuelte todas las sessiones.
     *
     * @return array
     */
    public static function all()
    {
        $sessions = array();

        $sessionPrefix = self::$prefix;
        $sessionPrefixLength = strlen($sessionPrefix);

        foreach ( $_SESSION as $name => $data )
        {
            if ( strncasecmp($name, $sessionPrefix, $sessionPrefixLength) == 0 )
                $name = substr($name, $sessionPrefixLength);

            $sessions[$name] = self::decode($data);
        }

        return $sessions;
    }

    /**
     * Codifica los datos para almacenarlos en la sesión.
     *
     * @param mixed $data
     * @return string
     */
    protected static function encode( $data )
    {
        return base64_encode( serialize($data) );
    }

    /**
     * Descodifica los datos.
     *
     * @param string $data
     * @return mixed
     */
    protected static function decode( $data )
    {
        return unserialize( base64_decode($data) );
    }
}