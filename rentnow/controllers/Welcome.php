<?php

class Welcome extends Controller
{
    const EFECTO_INTERVAL = 'interval';
    const EFECTO_HOVER = 'hover';

    public static $efectos = array(
        self::EFECTO_INTERVAL => 'Rotar las fotografías cada 7 segundos',
        self::EFECTO_HOVER    => 'Rotar las fotografías al pasar el mouse por encima'
    );

    public function get()
    {
        $configuracion = Configuration::getInstance();

        $idioma    = Translate::locale();
        $inmuebles = Inmuebles::allContenido(array( 'inmuebles.activo' => 1 ), null, null, $idioma);

        foreach ( $inmuebles as $i => $inmueble )
        {
            if ( !( isset($inmueble->contenidos[$idioma]) ) )
            {
                unset($inmuebles[$i]); continue;
            }

            $inmueble->fotos = Inmuebles_Fotos::all(array('inmueble_id' => $inmueble->id), array('posicion'));
        }

        $twitter = null;

        if ( !( empty($configuracion->twitter) ) )
            $twitter = $this->twitter($configuracion->twitter);

        $title = $configuracion->getContent('title', $idioma); # __('Principal');

        if ( null === $title )
            $title = __('Principal');

        return array(
            'title'     => $title,
            'nombre'    => $configuracion->nombre,
            'efecto'    => $configuracion->efecto,
            'twitter'   => $twitter,
            'idioma'    => $idioma,
            'inmuebles' => $inmuebles
        );
    }

    public function twitter( $username )
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://twitter.com/users/show/$username.xml");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        curl_close($ch);

        if ( class_exists('SimpleXMLElement') )
            return new SimpleXMLElement($response);

        return null;
    }
}