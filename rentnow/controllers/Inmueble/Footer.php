<?php

class Inmueble_Footer extends Controller
{
    public function get()
    {
        $configuracion = Configuration::getInstance();

        return array(
            'nombre' => $configuracion->nombre
        );
    }
}