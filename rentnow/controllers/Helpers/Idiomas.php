<?php

class Helpers_Idiomas extends Controller
{
    protected $idiomas;

    public function init()
    {
        $idiomas = Translate::all();
        $locale  = Translate::locale();

        foreach ( $idiomas as $i => $idioma )
            if ( !( $idioma == $locale ) )
                $this->idiomas[] = $idioma;

        return true;
    }

    public function get()
    {
        return array(
            'idiomas' => $this->idiomas
        );
    }
}