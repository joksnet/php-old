<?php

class Sitemap extends Controller
{
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