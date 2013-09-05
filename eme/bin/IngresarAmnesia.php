<?php

class IngresarAmnesia extends WebBase
{
    public $type = 'text/html';

    public function __construct()
    {
        if ( Session::isLogin() )
            Response::setRedirect('/');
    }
}