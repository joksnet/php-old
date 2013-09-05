<?php

include_once 'Wax/Controller.php';
include_once 'Wax/Request.php';
include_once 'Wax/Response.php';

class Wax_Controller_JavaScript extends Wax_Controller
{
    protected static $_instance = null;

    protected $_modulesPath = '';
    protected $_libraryPath = '';
    protected $_defaultExt = 'js';

    /**
     * @return Wax_Controller_JavaScript
     */
    public static function getInstance()
    {
        if ( is_null(self::$_instance) )
            self::$_instance = new self();

        return self::$_instance;
    }

    /**
     * @return Wax_Controller_JavaScript
     */
    public function setModulesPath( $modulesPath )
    {
        if ( strlen($modulesPath) > 0 )
            $this->_modulesPath = $modulesPath;

        return $this;
    }

    /**
     * @return Wax_Controller_JavaScript
     */
    public function setLibraryPath( $libraryPath )
    {
        if ( strlen($libraryPath) > 0 )
            $this->_libraryPath = $libraryPath;

        return $this;
    }

    public function dispatch()
    {
        $fileName = Wax_Request::getInstance()->getRequestUri();
        $fileName = str_replace('/@JavaScript/', '', $fileName);
        $fileName = str_replace('/', '', $fileName);
        $fileName = str_replace('_', '/', $fileName);

        $filePathModules = $this->_modulesPath . $fileName . '.' . $this->_defaultExt;
        $filePathLibrary = $this->_libraryPath . $fileName . '.' . $this->_defaultExt;

        if ( file_exists($filePathModules) )
            $readFile = file_get_contents($filePathModules);
        elseif ( file_exists($filePathLibrary) )
            $readFile = file_get_contents($filePathLibrary);
        else
            $readFile = '';

        Wax_Response::getInstance()
            ->setHeader('Content-Type', 'text/javascript')
            ->appendBody( $readFile )
            ->sendResponse();
    }
}