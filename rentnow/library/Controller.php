<?php

class Controller
{
    public function validation()
    {
        return array();
    }

    public function get()
    {
        return array();
    }

    public function post()
    {
        return false;
    }

    public static function dispatch( $class )
    {
        if ( !( class_exists($class) ) )
            include_once 'controllers/' . str_replace('_', '/', $class) . '.php';

        $instance = new $class();

        $params = array_slice(func_get_args(), 1);
        $return = true;

        if ( method_exists($instance, 'init') )
        {
            $return = call_user_func_array(
                array($instance, 'init'), $params
            );
        }

        if ( !( true === $return ) )
        {
            if ( is_string($return) || $return instanceof Url )
                Url::redirect($return);

            return false;
        }

        $errors = array();

        if ( Request::isPost() )
        {
            $validation = new Validate();

            if ( $rules = $instance->validation() )
            {
                $validation->add($rules);
                $validation->validate();
            }

            if ( $validation->valid() )
            {
                $return = $instance->post();

                if ( !( false === $return ) )
                {
                    if ( is_string($return) || $return instanceof Url )
                        Url::redirect($return);

                    return true;
                }
            }

            $errors = $validation->errors();
        }

        $return = $instance->get();

        if ( !( false === $return ) )
        {
            $return['errors'] = $errors;

            $view = new View($class, $return);
            $viewContent = $view->dispatch();

            return $viewContent;
        }

        return false;
    }
}