<?php

class Error404 extends Controller
{
    public function init()
    {
        Response::setHttpResponseCode(404);

        // Response::setHeader('HTTP/1.0 404 Not Found');
        // Response::setBody('404', 'Error 404');

        return true;
    }

    public function get()
    {
        $configuracion = Configuration::getInstance();

        return array(
            'nombre' => $configuracion->nombre
        );
    }
}