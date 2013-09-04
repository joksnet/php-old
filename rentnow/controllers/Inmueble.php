<?php

class Inmueble extends Controller
{
    public function init( $url )
    {
        $idioma = Translate::locale();
        $inmuebles = Inmuebles_Contenidos::allInmueble(array(
            'idioma' => $idioma,
            'url'    => $url
        ), null, 1);

        if ( $inmuebles )
        {
            $inmueble = array_pop($inmuebles);

            if ( $inmueble->inmueble_pagina_id_inicio > 0 && !( empty($inmueble->diseno) ) )
            {
                $paginas = Inmuebles_Paginas_Contenidos::allPagina(array(
                    'inmuebles_paginas_contenidos.inmueble_pagina_id' => $inmueble->inmueble_pagina_id_inicio,
                    'inmuebles_paginas_contenidos.idioma'             => $idioma
                ), null, 1);

                if ( $paginas )
                {
                    $pagina = array_pop($paginas);

                    if ( !( empty($pagina->url) ) )
                        return "/$url/$pagina->url";
                }
            }
        }

        return false;
    }
}