<?php

class Admin_Ayuda extends Controller
{
    public function init()
    {
        if ( !( Session::getInstance()->usuario ) )
            return '/admin/ingresar';

        return true;
    }
}