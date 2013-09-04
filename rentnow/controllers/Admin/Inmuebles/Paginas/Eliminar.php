<?php

class Admin_Inmuebles_Paginas_Eliminar extends Controller
{
    protected $inmuebleCodigo;
    protected $inmueble;

    protected $paginaCodigo;
    protected $pagina;

    public function init( $inmueble, $pagina )
    {
        if ( !( Session::getInstance()->usuario ) )
            return '/admin/ingresar';

        $this->inmuebleCodigo = $inmueble;
        $this->inmueble = new Inmuebles();
        $this->inmueble->codigo = $inmueble;
        $this->inmueble->queryAll();

        if ( !( $this->inmueble->found() ) )
            return true;

        $this->paginaCodigo = $pagina;
        $this->pagina = new Inmuebles_Paginas();
        $this->pagina->inmueble_id = $this->inmueble->id;
        $this->pagina->codigo = $pagina;
        $this->pagina->queryAll();

        return true;
    }

    public function get()
    {
        return array(
            'inmuebleCodigo' => $this->inmuebleCodigo,
            'inmueble'       => $this->inmueble,

            'paginaCodigo'   => $this->paginaCodigo,
            'pagina'         => $this->pagina
        );
    }

    public function post()
    {
        Inmuebles_Paginas::destroy(
            $this->pagina
        );

        return "/admin/inmuebles/$this->inmuebleCodigo/paginas?deleted=$this->paginaCodigo";
    }
}