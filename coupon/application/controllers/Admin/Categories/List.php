<?php

class Admin_Categories_List extends Controller
{
    public function init()
    {
        if ( !( $this->session->login ) )
            return '/admin/login';

        return true;
    }

    public function get()
    {
        include_once 'application/models/Categories.php';

        $page = $this->request->getQuery('page', 1);
        $per  = $this->request->getQuery('per', 20);

        $query = Categories::query();
        $count = $query->count();

        $categories = $query
            ->order('name')
            ->limit($per, ( $page - 1 ) * $per)
            ->fetch();

        return array(
            'page' => $page,
            'per'  => $per,

            'count'      => $count,
            'categories' => $categories
        );
    }
}