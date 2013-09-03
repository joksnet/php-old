<?php

class Helpers_Header extends Controller
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
}