<?php

class Session
{
    protected static $readed = false;
    protected static $started = false;

    public static $id = false;
    public static $nombre = false;
    public static $usuario = false;
    public static $contrasena = false;

    protected static function read()
    {
        if ( self::$readed )
            return;

        $data = Cookies::get('DATA');

        if ( $data )
        {
            $data = unserialize( base64_decode($data) );

            self::$readed = true;
            self::$usuario = $data['usuario'];
            self::$contrasena = $data['contrasena'];
        }
    }

    public static function start()
    {
        if ( self::$started )
            return;

        self::read();
        self::$started = true;

        $result = Db::row(
            "SELECT u.id_personas
                  , CONCAT(u.nombre, ' ', u.apellido) AS nombre
             FROM personas u
             WHERE u.usuario = '" . self::$usuario . "'
               AND u.contrasena = '" . self::$contrasena . "'
             LIMIT 1"
        );

        self::$id = $result['id_personas'];
        self::$nombre = $result['nombre'];
    }

    public static function register( $usuario, $contrasena, $remember = false )
    {
        if ( strlen($contrasena) != 32 )
            $contrasena = md5( $contrasena );

        self::$usuario = $usuario;
        self::$contrasena = $contrasena;
        self::$readed = true;
        self::start();

        $data = array(
            'usuario'    => self::$usuario,
            'contrasena' => self::$contrasena
        );

        if ( $remember )
            $expires = 3600 * 24 * 15; // 15 Dias
        else
            $expires = 3600; // 1 Hora

        Cookies::set('DATA', base64_encode( serialize($data) ), $expires);
    }

    public static function unregister()
    {
        Cookies::del('DATA');
    }

    public static function uid()
    {
        return self::$id;
    }

    public static function isLogin()
    {
        return ( self::$id && self::$id > 0 );
    }
}