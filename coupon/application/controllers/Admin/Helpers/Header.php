<?php

class Admin_Helpers_Header extends Controller
{
    protected $title;

    public function init( $title )
    {
        $this->title = $title;

        return true;
    }

    public function get()
    {
        return array(
            'title' => $this->title
        );
    }

    public function post()
    {
        return false;
    }
}