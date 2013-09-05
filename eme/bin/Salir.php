<?php

class Salir extends WebBase
{
    public function __construct()
    {
        parent::__construct();

        Session::start();
        Session::unregister();

        Response::setRedirect('/ingresar');
    }
}