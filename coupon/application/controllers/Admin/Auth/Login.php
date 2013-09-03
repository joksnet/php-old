<?php

class Admin_Auth_Login extends Controller
{
    public function init()
    {
        if ( $this->session->login )
            return '/admin';

        return true;
    }

    public function get()
    {
        return array();
    }

    public function post()
    {
        $this->session->user = Request::getPost('user');
        $this->session->login = true;

        return '/admin';
    }
}