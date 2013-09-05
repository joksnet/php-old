<?php

class WebBase
{
    public $type = 'text/plain';

    public function __construct()
    {
        if ( !( Session::isLogin() ) )
            Response::setRedirect('/ingresar');
    }

    public function dispatch()
    {
        global $root;

        $className = get_class($this);
        $fileName  = str_replace('_', DIRECTORY_SEPARATOR, $className);
        $template  = Config::get('template');

        if ( is_readable("$root/tpl/$template/$fileName.php") )
            $include = "$root/tpl/$template/$fileName.php";
        elseif ( is_readable("$root/tpl/default/$fileName.php") )
            $include = "$root/tpl/default/$fileName.php";
        else
            return;

        include_once $include;
    }
}