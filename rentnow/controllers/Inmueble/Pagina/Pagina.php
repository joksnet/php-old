<?php

class Inmueble_Pagina_Pagina extends Controller
{
    protected $inmueble;
    protected $pagina;

    public function init( $inmueble, $pagina )
    {
        $this->inmueble = $inmueble;
        $this->pagina = $pagina;

        if ( !( $this->inmueble->found() ) )
            return false;

        if ( !( $this->pagina->found() ) )
            return false;

        return true;
    }

    public function get()
    {
        return array(
            'inmueble'  => $this->inmueble,
            'pagina'    => $this->pagina
        );
    }
}