<?php

class SitemapXML extends Controller
{
    public static $type = 'text/xml';

    public function get()
    {
        $idioma = Translate::locale();
        $inmuebles = Inmuebles::allPaginas(array(
            'inmuebles_contenidos.idioma' => $idioma,
            'inmuebles_paginas.menu'      => 1
        ), $idioma);

        return array(
            'inmuebles' => $inmuebles
        );
    }
}