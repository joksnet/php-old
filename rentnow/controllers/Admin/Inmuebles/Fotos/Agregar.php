<?php

class Admin_Inmuebles_Fotos_Agregar extends Controller
{
    protected $unique = true;

    protected $inmuebleCodigo;
    protected $inmueble;

    protected $codigo = '';

    public function init( $inmueble )
    {
        if ( !( Session::getInstance()->usuario ) )
            return '/admin/ingresar';

        $this->inmuebleCodigo = $inmueble;
        $this->inmueble = new Inmuebles();
        $this->inmueble->codigo = $this->inmuebleCodigo;
        $this->inmueble->queryAll();

        if ( !( $this->inmueble->found() ) )
            return true;

        $new = '';

        if ( Request::hasPost('codigo') )
            $this->codigo = Request::getPost('codigo', '');
        else
            $this->codigo = str_pad(Inmuebles_Fotos::count(array('inmueble_id' => $this->inmueble->id)) + 1, 4, '0', STR_PAD_LEFT);

        return true;
    }

    public function validation()
    {
        return array(
            'codigo' => 'code[4:20]',
            'foto'   => array(
                'file'     => true,
                'fileMax'  => 5242880, // 5Mb
                'fileType' => Validate::FILETYPE_IMAGE
            )
        );
    }

    public function get()
    {
        return array(
            'unique'         => $this->unique,
            'inmuebleCodigo' => $this->inmuebleCodigo,
            'inmueble'       => $this->inmueble,
            'codigo'         => $this->codigo
        );
    }

    public function post()
    {
        $foto = new Inmuebles_Fotos();
        $foto->inmueble_id = $this->inmueble->id;
        $foto->codigo = strtolower(
            $this->codigo
        );

        if ( $foto->queryAll() )
            return ( $this->unique = false ) && false;

        $foto->nombre = $_FILES['foto']['name'];
        $foto->posicion = Inmuebles_Fotos::pos(
            $this->inmueble->id
        ) + 1;

        $id = $foto->insert();

        Inmuebles_Fotos::upload($_FILES['foto']['tmp_name'],
            $this->inmuebleCodigo,
            $foto->codigo
        );

        return "/admin/inmuebles/$this->inmuebleCodigo/fotos/$foto->codigo?added=1";
    }
}