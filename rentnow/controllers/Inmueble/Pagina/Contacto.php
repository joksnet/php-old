<?php

class Inmueble_Pagina_Contacto extends Controller
{
    protected $inmueble;
    protected $pagina;

    protected $datos;
    protected $html = '';

    public function init( $inmueble, $pagina )
    {
        $this->inmueble = $inmueble;
        $this->pagina = $pagina;

        if ( !( $this->inmueble->found() ) )
            return false;

        if ( !( $this->pagina->found() ) )
            return false;

        $this->datos = Inmuebles_Paginas_Datos::allPairs(array(
            'inmueble_pagina_id' => $this->pagina->inmueble_pagina_id
        ));

        $datos = Inmuebles_Paginas_Contenidos_Datos::allPairs(array(
            'inmueble_pagina_contenido_id' => $this->pagina->id
        ));

        if ( isset($datos['html']) )
            $this->html = $datos['html']->contenido;

        return true;
    }

    public function validation()
    {
        return array(
            'nombre'  => 'required',
            'correo'  => array( 'required', 'email' ),
            'mensaje' => 'required'
        );
    }

    public function get()
    {
        return array(
            'inmueble'  => $this->inmueble,
            'pagina'    => $this->pagina,

            'datos'     => $this->datos,
            'html'      => $this->html,

            'nombre'    => Request::getPost('nombre', ''),
            'correo'    => Request::getPost('correo', ''),
            'mensaje'   => Request::getPost('mensaje', '')
        );
    }

    public function post()
    {
        if ( !( '' == Request::getPost('fake', '') ) )
            return false;

        if ( !( isset($this->datos['correo']) ) || ( isset($this->datos['correo']) && empty($this->datos['correo']->contenido) ) )
            return false;

        $to = $this->datos['correo']->contenido;

        $nombre  = Request::getPost('nombre', '');
        $correo  = Request::getPost('correo', '');
        $mensaje = Request::getPost('mensaje', '');

        $nombreLang  = __('Nombre y Apellidos');
        $correoLang  = __('Correo Electrónico');
        $mensajeLang = __('Mensaje');

        $text = __('Nombre: $0\nCorreo: $1\nMensaje:\n$2', $nombre, $correo, $mensaje);
        $html = <<<HTML
<p>$nombreLang: $nombre</p>
<p>$correoLang: $correo</p>
<p>$mensajeLang:<br />$mensaje</p>
HTML;

        DefinedMail::from('info@buenosairesrentnow.com', 'BsAs Rent Now');
        DefinedMail::mail('[BARN] ' . __('Mensaje de $0', $nombre))
            ->to($to)
            ->text($text)
            ->html($html)
            ->send();

        return new Url(array(
            'ok' => 1
        ));
    }
}