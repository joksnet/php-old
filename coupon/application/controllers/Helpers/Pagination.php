<?php

class Helpers_Pagination extends Controller
{
    protected $count;
    protected $page;
    protected $per;

    public function init( $count, $page = 1, $per = 20 )
    {
        $this->count = $count;
        $this->page = $page;
        $this->per = $per;

        return true;
    }

    public function get()
    {
        $pages = ceil($this->count / $this->per);

        return array(
            'count'   => $this->count,
            'page'    => $this->page,
            'perpage' => $this->per,
            'pages'   => $pages
        );
    }
}