<?php

class Admin_Inmuebles_Fotos_Posicion extends Controller
{
    protected $inmuebleCodigo;
    protected $inmueble;

    protected $fotoCodigo;
    protected $foto;

    protected $pos;
    protected $new;

    protected $signo;
    protected $cantidad;

    public function init( $inmueble, $foto, $mover )
    {
        if ( !( Session::getInstance()->usuario ) )
            return '/admin/ingresar';

        $this->inmuebleCodigo = $inmueble;
        $this->inmueble = new Inmuebles();
        $this->inmueble->codigo = $this->inmuebleCodigo;
        $this->inmueble->queryAll();

        if ( !( $this->inmueble->found() ) )
            return true;

        $this->fotoCodigo = $foto;
        $this->foto = new Inmuebles_Fotos();
        $this->foto->inmueble_id = $this->inmueble->id;
        $this->foto->codigo = $this->fotoCodigo;
        $this->foto->queryAll();

        if ( !( $this->foto->found() ) )
            return true;

        $this->pos = Inmuebles_Fotos::pos($this->inmueble->id);
        $this->new = false;

        $this->signo    = substr($mover, 0, 1);
        $this->cantidad = intval(substr($mover, 1));

        switch ( $this->signo )
        {
            case '+':
                $pos = $this->foto->posicion + $this->cantidad;

                if ( $pos <= $this->pos )
                    $this->new = $pos;
                break;

            case '-':
                $pos = $this->foto->posicion - $this->cantidad;

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

            'fotoCodigo'     => $this->fotoCodigo,
            'foto'           => $this->foto,

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

        $this->foto->move(
            $this->new
        );

        return "/admin/inmuebles/$this->inmuebleCodigo/fotos/$this->fotoCodigo?moved=$this->signo$this->cantidad";
    }
}