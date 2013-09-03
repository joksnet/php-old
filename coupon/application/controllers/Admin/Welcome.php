<?php

class Admin_Welcome extends Controller
{
    public function init()
    {
        if ( !( $this->session->login ) )
            return '/admin/login';

        return true;
    }

    public function get()
    {
        return array();
    }
}