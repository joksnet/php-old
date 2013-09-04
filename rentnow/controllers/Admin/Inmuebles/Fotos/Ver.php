<?php

class Admin_Inmuebles_Fotos_Ver extends Controller
{
    protected $inmuebleCodigo;
    protected $inmueble;

    protected $fotoCodigo;
    protected $foto;

    public function init( $inmueble, $foto )
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

        return true;
    }

    public function get()
    {
        return array(
            'inmuebleCodigo' => $this->inmuebleCodigo,
            'inmueble'       => $this->inmueble,

            'fotoCodigo'     => $this->fotoCodigo,
            'foto'           => $this->foto
        );
    }
}