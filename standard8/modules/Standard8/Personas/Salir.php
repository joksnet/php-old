<?php

class Personas_Salir
{
    public function __construct( Standard8 $Standard8 )
    {
        Standard8_Session::destroy();
        Wax_Response::getInstance()->setRedirect('/');
    }
}