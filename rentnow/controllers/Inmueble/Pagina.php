<?php

class Inmueble_Pagina extends Controller
{
    protected $inmueble;
    protected $pagina;

    protected $idioma;
    protected $paginas;

    public function init( $inmuebleURL, $paginaURL )
    {
        $idioma    = Translate::locale();
        $inmuebles = Inmuebles_Contenidos::allInmueble(
            array('idioma' => $idioma, 'url' => $inmuebleURL), null, 1
        );

        if ( $inmueble = array_pop($inmuebles) )
        {
            $paginas = Inmuebles_Paginas_Contenidos::allPagina(
                array('idioma' => $idioma, 'url' => $paginaURL), null, 1
            );

            if ( $pagina = array_pop($paginas) )
            {
                $this->idioma   = $idioma;
                $this->inmueble = $inmueble;
                $this->pagina   = $pagina;

                return true;
            }
        }

        return false;
    }

    public function get()
    {
        $configuracion = Configuration::getInstance();

        return array(
            'nombre' => $configuracion->nombre,

            'inmueble' => $this->inmueble,
            'pagina'   => $this->pagina
        );
    }
}