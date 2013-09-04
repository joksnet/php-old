<?php

class Admin_Inmuebles_Paginas_Posicion extends Controller
{
    protected $inmuebleCodigo;
    protected $inmueble;

    protected $paginaCodigo;
    protected $pagina;

    protected $pos;
    protected $new;

    protected $signo;
    protected $cantidad;

    public function init( $inmueble, $pagina, $mover )
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

        $this->pos = Inmuebles_Paginas::pos($this->inmueble->id);
        $this->new = false;

        $this->signo = substr($mover, 0, 1);
        $this->cantidad = intval(substr($mover, 1));

        switch ( $this->signo )
        {
            case '+':
                $pos = $this->pagina->posicion + $this->cantidad;

                if ( $pos <= $this->pos )
                    $this->new = $pos;
                break;

            case '-':
                $pos = $this->pagina->posicion - $this->cantidad;

                if ( $pos > 0 )
                    $this->new = $pos;
                break;
        }

        return true;
    }

    public function get()
    {
        return array(
            'inmuebleCodigo' => $this->inmuebleCodigo,
            'inmueble'       => $this->inmueble,

            'paginaCodigo'   => $this->paginaCodigo,
            'pagina'         => $this->pagina,

            'pos'            => $this->pos,
            'new'            => $this->new,

            'signo'          => $this->signo,
            'cantidad'       => $this->cantidad
        );
    }

    public function post()
    {
        if ( false === $this->new )
            return false;

        $this->pagina->move(
            $this->new
        );

        return "/admin/inmuebles/$this->inmuebleCodigo/paginas/$this->paginaCodigo?moved=$this->signo$this->cantidad";
    }
}