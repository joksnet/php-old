<?php

class Admin_Categories_Empty extends Controller
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

    public function post()
    {
        # include_once 'application/models/Categories.php';

        $this->db->truncate(
            Model::table('Categories')
        );

        return '/admin/categories';
    }
}