<?php

class Controller
{
    /**
     * @var Db
     */
    protected $db;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var Session
     */
    protected $session;

    /**
     * ---
     */
    public function __construct()
    {
        $this->db = Db::getInstance();
        $this->request = Request::getInstance();
        $this->response = Response::getInstance();
        $this->session = Session::getInstance();
    }

    /**
     * @param string $class
     * @return boolean|string
     */
    public static function dispatch( $class )
    {
        if ( !( class_exists($class) ) )
            include_once 'application/controllers/' . str_replace('_', '/', $class) . '.php';

        $instance = new $class();
        $return = true;

        if ( method_exists($instance, 'init') )
        {
            $params = array_slice(func_get_args(), 1);
            $return = call_user_func_array(
                array($instance, 'init'), $params
            );

            if ( !( true === $return ) )
            {
                if ( is_string($return) )
                    Url::redirect($return);

                return false;
            }
        }

        if ( Request::getInstance()->isPost() )
        {
            if ( !( method_exists($instance, 'post') ) )
                return false;
            else
            {
                $return = $instance->post();

                if ( is_string($return) )
                    Url::redirect($return);
                else
                {
                    $url = new Url();

                    if ( false === $return )
                        $url->addQuery('error', true);

                    Url::redirect($url);
                }

                return true;
            }
        }

        if ( method_exists($instance, 'get') )
        {
            $return = $instance->get();

            if ( !( false === $return ) )
            {
                $view = new View($class, $return);
                $viewContent = $view->__toString();

                return $viewContent;
            }
        }

        return false;
    }
}