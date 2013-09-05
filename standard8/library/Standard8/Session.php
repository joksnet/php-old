<?php

include_once 'Wax/Db.php';
include_once 'Wax/Request.php';

final class Standard8_Session
{
    private static $_sessionStarted = false;
    private static $_sessionDestroyed = false;

    private static $_idSesiones = null;
    private static $_data = null;

    public static function start()
    {
        if ( self::$_sessionStarted )
            return;

        self::$_sessionStarted = true;
        self::$_sessionDestroyed = false;

        $requestSID = Wax_Request::getInstance()->SID;

        if ( !( empty($requestSID) ) )
            self::$_idSesiones = $requestSID;
        elseif ( isset($_COOKIE['Standard8_Session_SID']) )
            self::$_idSesiones = $_COOKIE['Standard8_Session_SID'];

        if ( strlen(self::$_idSesiones) > 0 )
        {
            self::$_data = Wax_Db::select()
                ->from( array('s' => TABLE_SESIONES), array('fecha') )
                ->where('id_sesiones', self::$_idSesiones)
                ->joinInner( array('p' => TABLE_PERSONAS)
                  , 'p.id_personas = s.id_personas'
                  , array(
                        'usuario'
                      , 'clave'
                  )
                )->query()->fetchRow();

            if ( empty(self::$_data) )
                self::destroy();
        }
    }

    public static function destroy()
    {
        if ( self::$_sessionDestroyed )
            return;

        if ( !( self::$_sessionStarted ) )
            self::start();

        self::$_sessionStarted = false;
        self::$_sessionDestroyed = true;

        Wax_Db::delete(TABLE_SESIONES)
            ->where('id_sesiones', self::$_idSesiones)
            ->query();

        self::$_data = null;
        self::$_idSesiones = null;
    }

    public static function begin( $idPersonas )
    {
        $fechaIngreso = time();
        $idSesiones = md5( $fechaIngreso );

        Wax_Db::delete(TABLE_SESIONES)
            ->where('id_personas', $idPersonas)
            ->query();

        Wax_Db::insert(TABLE_SESIONES)
            ->set('id_sesiones', $idSesiones)
            ->set('id_personas', $idPersonas)
            ->set('fecha', $fechaIngreso)
            ->query();

        setcookie(
            'Standard8_Session_SID', $idSesiones, 0, '/', '', ''
        );

        return $idSesiones;
    }

    public static function getData( $key )
    {
        if ( self::$_data && is_array(self::$_data) )
            if ( isset(self::$_data[$key]) )
                return self::$_data[$key];

        return null;
    }

    public static function getSID()
    {
        return self::$_idSesiones;
    }
}