<?php

class Admin_Inmuebles_Paginas_Mover extends Controller
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

        if ( !( $this->pagina->found() ) )
            return true;

        return true;
    }

    public function validation()
    {
        return array(
            'signo'    => 'required[1:1]',
            'cantidad' => 'number'
        );
    }

    public function get()
    {
        return array(
            'inmuebleCodigo' => $this->inmuebleCodigo,
            'inmueble'       => $this->inmueble,

            'paginaCodigo'   => $this->paginaCodigo,
            'pagina'         => $this->pagina,

            'signo'          => Request::getPost('signo', '+'),
            'cantidad'       => Request::getPost('cantidad', 1)
        );
    }

    public function post()
    {
        $signo = Request::getPost('signo', '+');
        $cantidad = Request::getPost('cantidad', 1);

        return "/admin/inmuebles/$this->inmuebleCodigo/paginas/$this->paginaCodigo/mover$signo$cantidad";
    }
}