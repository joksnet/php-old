<?php

class Logout
{
    public function __construct()
    {
        Cookies::del('sid');
        Cookies::del('uid');
        Cookies::del('data');

        header('Location: /');
    }
}