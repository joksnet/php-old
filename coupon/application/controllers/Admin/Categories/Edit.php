<?php

include_once 'application/models/Categories.php';

class Admin_Categories_Edit extends Controller
{
    /**
     * @var Categories
     */
    protected $category;

    public function init( $id )
    {
        if ( !( $this->session->login ) )
            return '/admin/login';

        $this->category = new Categories($id);

        if ( null === $this->category->id )
            return false;

        return true;
    }

    public function get()
    {
        return array(
            'category' => $this->category,
            'name'     => $this->request->getPost('name', $this->category->name)
        );
    }

    public function post()
    {
        $name = $this->request->getPost('name');

        if ( empty($name) )
            return false;

        $this->category->name = $name;
        $this->category->put();

        return "/admin/categories/{$this->category->id}";
    }
}