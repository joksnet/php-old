<?php

class Admin_Businesses_List extends Controller
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