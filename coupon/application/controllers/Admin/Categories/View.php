<?php

include_once 'application/models/Categories.php';

class Admin_Categories_View extends Controller
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
            'category' => $this->category
        );
    }
}