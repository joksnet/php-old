<?php

class Admin_Salir extends Controller
{
    public function init()
    {
        unset(
            Session::getInstance()->usuario
        );

        Session::destroy();

        return '/admin/ingresar';
    }
}