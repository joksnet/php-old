<?php

include_once 'Wax/Controller.php';
include_once 'Wax/Request.php';
include_once 'Wax/Response.php';

class Wax_Controller_Profile extends Wax_Controller
{
    protected static $_instance = null;

    protected $_profileClass = '';
    protected $_profileChar = '~';

    /**
     * @return Wax_Controller_Profile
     */
    public static function getInstance()
    {
        if ( is_null(self::$_instance) )
            self::$_instance = new self();

        return self::$_instance;
    }

    /**
     * @return Wax_Controller_Profile
     */
    public function setProfileClass( $profileClass )
    {
        if ( strlen($profileClass) > 0 )
            $this->_profileClass = $profileClass;

        return $this;
    }

    public function dispatch()
    {

    }
}