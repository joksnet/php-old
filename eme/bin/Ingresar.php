<?php

class Ingresar extends WebBase
{
    public $type = 'text/html';

    protected $usuario = '';
    protected $contrasena = '';

    protected $validationFlag = true;
    protected $validation = null;

    public function __construct()
    {
        if ( Session::isLogin() )
            Response::setRedirect('/');
    }

    public function submit()
    {
        $this->usuario    = Request::getPost('usuario');
        $this->contrasena = md5( Request::getPost('contrasena') );
        $this->recordar   = Request::getPost('recordar', 0);

        list($this->validationFlag, $this->validation) = Validation::check(array(
            'usuario'    => 'required',
            'contrasena' => 'required'
        ));

        if ( $this->validationFlag )
        {
            $idPersonas = Db::one(
                "SELECT personas.id_personas
                   FROM personas
                  WHERE personas.usuario = '$this->usuario'
                    AND personas.contrasena = '$this->contrasena'
                  LIMIT 1"
            );

            if ( $idPersonas )
            {
                Session::unregister();
                Session::register($this->usuario, $this->contrasena, $this->recordar == 1);

                Response::setRedirect('/');
            }

            $this->validationFlag = false;
        }
    }
}