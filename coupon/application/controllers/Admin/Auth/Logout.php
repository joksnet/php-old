<?php

class Admin_Auth_Logout extends Controller
{
    public function init()
    {
        if ( !( $this->session->login ) )
            return '/admin/login';

        unset($this->session->user);
        unset($this->session->login);

        return '/admin/login';
    }
}