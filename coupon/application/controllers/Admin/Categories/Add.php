<?php

class Admin_Categories_Add extends Controller
{
    public function init()
    {
        if ( !( $this->session->login ) )
            return '/admin/login';

        return true;
    }

    public function get()
    {
        return array(
            'name' => $this->request->getPost('name', '')
        );
    }

    public function post()
    {
        include_once 'application/models/Categories.php';

        $name = $this->request->getPost('name');

        if ( empty($name) )
            return false;

        $category = new Categories();
        $category->name = $name;
        $category->put();

        return "/admin/categories/$category->id";
    }
}