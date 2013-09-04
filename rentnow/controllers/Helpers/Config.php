<?php

class Helpers_Config extends Controller
{
    protected $config;

    public function init( array $config )
    {
        $this->config = $config;

        return true;
    }

    public function get()
    {
        return $this->config;
    }
}