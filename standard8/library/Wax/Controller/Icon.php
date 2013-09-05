<?php

include_once 'Wax/Controller.php';
include_once 'Wax/Request.php';
include_once 'Wax/Response.php';

class Wax_Controller_Icon extends Wax_Controller
{
    protected static $_instance = null;

    protected $_iconPath = '';
    protected $_defaultExt = 'png';

    /**
     * @return Wax_Controller_Icon
     */
    public static function getInstance()
    {
        if ( is_null(self::$_instance) )
            self::$_instance = new self();

        return self::$_instance;
    }

    /**
     * @return Wax_Controller_Icon
     */
    public function setIconPath( $iconPath )
    {
        if ( strlen($iconPath) > 0 )
            $this->_iconPath = $iconPath;

        return $this;
    }

    public function dispatch()
    {
        $fileName = Wax_Request::getInstance()->getRequestUri();
        $fileName = str_replace('/@@/', '', $fileName);
        $fileName = str_replace('/', '', $fileName);

        $filePath = $this->_iconPath . $fileName . '.' . $this->_defaultExt;
        $readFile = file_get_contents($filePath);

        Wax_Response::getInstance()
            ->setHeader('Content-Type', 'image/' . $this->_defaultExt)
            ->appendBody( $readFile )
            ->sendResponse();
    }
}