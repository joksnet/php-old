<?php

include_once 'Wax/Controller.php';
include_once 'Wax/Factory.php';
include_once 'Wax/Request.php';
include_once 'Wax/Response.php';
include_once 'Wax/Document.php';
include_once 'Wax/Locale.php';
include_once 'Wax/Messages.php';

class Wax_Controller_Front extends Wax_Controller
{
    protected static $_instance = null;

    protected $_moduleBase = '';
    protected $_moduleDefault = '';
    protected $_moduleForce = '';

    protected $_classBase = '';
    protected $_classDefault = '';
    protected $_classForce = '';

    protected $_dispatched = false;
    protected $_binaryPath = '';

    /**
     * @var Wax_Session_Namespace
     */
    protected $_controllerData = null;

    /**
     * @return Wax_Controller_Front
     */
    public static function getInstance()
    {
        if ( is_null(self::$_instance) )
            self::$_instance = new self();

        return self::$_instance;
    }

    public function __construct()
    {
        $this->_controllerData = Wax_Factory::createObject(
            'Wax_Session_Namespace', 'Wax_Controller'
        );
    }

    /**
     * @return Wax_Controller_Front
     */
    public function setBase( $className )
    {
        if ( is_array($className) )
        {
            $this->_moduleBase = array_shift($className);
            $this->_classBase = array_shift($className);
        }
        elseif ( strlen($className) > 0 )
            $this->_classBase = $className;

        return $this;
    }

    /**
     * @return Wax_Controller_Front
     */
    public function setDefault( $className )
    {
        if ( is_array($className) )
        {
            $this->_moduleDefault = array_shift($className);
            $this->_classDefault = array_shift($className);
        }
        elseif ( strlen($className) > 0 )
            $this->_classDefault = $className;

        return $this;
    }

    /**
     * @return Wax_Controller_Front
     */
    public function setForce( $className )
    {
        if ( is_array($className) )
        {
            $this->_moduleForce = array_shift($className);
            $this->_classForce = array_shift($className);
        }
        elseif ( strlen($className) > 0 )
            $this->_classForce = $className;

        return $this;
    }

    /**
     * @return Wax_Controller_Front
     */
    public function setBinaryPath( $binaryPath )
    {
        if ( strlen($binaryPath) > 0 )
            $this->_binaryPath = $binaryPath;

        return $this;
    }

    public function getClass()
    {
        $requestURI = Wax_Request::getInstance()->getRequestUri();
        $requestURI = explode('/', $requestURI );

        if ( is_array($requestURI) && sizeof($requestURI) > 0 )
        {
            foreach ( $requestURI as $key => $value )
                if ( strlen($value) == 0 )
                    unset($requestURI[$key]);

            # $requestURI = array_map('ucwords', $requestURI);

            $module = explode('_', $this->_moduleBase);
            $module = $module[0];

            return array($module, implode('_', $requestURI));
        }
        else
            return array();
    }

    protected function instanceClass( $classBase, $className, $parameters = array() )
    {
        $moduleName = $this->_moduleDefault;
        $classNameParameters = array();

        if ( is_array($className) )
        {
            $moduleName = ucfirst( array_shift($className) );
            $className = ucfirst( array_shift($className) );
            # $classParam = array($moduleName, $className);
        }
        else
        {
            $className = ucfirst($className);
            # $classParam = $className;
        }

        if ( $classBase )
            array_unshift($parameters, $classBase);

        # $className = implode('_', array_map('ucfirst', explode('_', $className) ) );

        do
        {
            $filePath = implode('/', array_map('ucfirst', explode('_', $className) ) );
            # $filePath = str_replace('_', '/', $className);
            $fileName = $this->_binaryPath
                      . '' . $moduleName
                      . '/' . $filePath
                      . '.php';
            $fileExists = file_exists($fileName);

            if ( !( $fileExists ) )
            {
                $classNamePieces = explode('_', $className);

                # if ( sizeof($classNamePieces) == 0 )
                #     return false;

                if ( sizeof($classNamePieces) > 0 )
                {
                    $classNamePiecesLast = array_pop($classNamePieces);

                    if ( strlen($classNamePiecesLast) == 0 )
                        return false;
                }
                else
                    return false;

                array_unshift($classNameParameters, $classNamePiecesLast);

                $className = implode('_', array_map('ucfirst', $classNamePieces) );
                # $className = implode('_', $classNamePieces );
            }

        } while ( !( $fileExists ) );

        $className = implode('_', array_map('ucfirst', explode('_', $className) ) );

        if ( sizeof($classNameParameters) > 0 )
            $parameters = array_merge($parameters, $classNameParameters);

        $classParam = array($moduleName, $className);

        try {
            $classInstance = Wax_Factory::createObject(
                $classParam, $parameters
            );
        } catch ( Wax_Exception $e ) {
            return false;
        }

        $classMethods = get_class_methods($classInstance);

        if ( 1 )
        {
            switch ( true )
            {
                case Wax_Request::getInstance()->isPost():
                    $result = false;

                    if ( in_array('__onSubmit', $classMethods, true) )
                    {
                        $args = array();

                        $classMethod = new ReflectionMethod($className, '__onSubmit');
                        $classMethodParams = $classMethod->getParameters();

                        foreach ( $classMethodParams as $parameter )
                        {
                            if ( $parameter->isDefaultValueAvailable() )
                                $args[] = Wax_Request::getInstance()->getPost(
                                    $parameter->getName()
                                  , $parameter->getDefaultValue()
                                );
                            else
                                $args[] = Wax_Request::getInstance()->getPost(
                                    $parameter->getName()
                                );
                        }

                        $result = $classMethod->invokeArgs(
                            $classInstance, $args
                        );
                    }

                    if ( Wax_Response::getInstance()->canSendHeaders() )
                    {
                        if ( is_array($result) )
                        {
                            $resultClass = $result[1];
                            $result = $result[0];

                            if ( is_array($resultClass) )
                                $resultUri = Standard8_Uri::createUri($resultClass[0], $resultClass[1]);
                            else
                                $resultUri = Standard8_Uri::createUri($resultClass);

                            Wax_Response::getInstance()->setRedirect($resultUri);
                        }
                        else
                        {
                            Wax_Response::getInstance()->setRedirect(
                                Wax_Request::getInstance()->getRequestUri()
                            );
                        }
                    }

                    break;
                case Wax_Request::getInstance()->isXmlHttpRequest() && in_array('__onXmlHttpRequest', $classMethods, true):
                    $args = array();

                    $classMethod = new ReflectionMethod($className, '__onXmlHttpRequest');
                    $classMethodParams = $classMethod->getParameters();

                    foreach ( $classMethodParams as $parameter )
                    {
                        if ( $parameter->isDefaultValueAvailable() )
                            $args[] = Wax_Request::getInstance()->get(
                                $parameter->getName()
                              , $parameter->getDefaultValue()
                            );
                        else
                            $args[] = Wax_Request::getInstance()->get(
                                $parameter->getName()
                            );
                    }

                    Wax_Response::getInstance()->appendBody(
                        $classMethod->invokeArgs($classInstance, $args)
                      , $className . '_XmlHttpRequest'
                    )->sendResponse();

                    return true;
                    break;
                default:
                    break;
            }

            return $classInstance;
        }

        return false;
    }

    public function dispatch()
    {
        if ( $this->_dispatched )
            return;

        $this->_dispatched = true;

        $requestURI = Wax_Request::getInstance()->getRequestUri();

        if ( strpos($requestURI, '?') !== false )
            $requestURI = substr($requestURI, 0, strpos($requestURI, '?'));

        $requestURI = explode('/', $requestURI );

        $parameters = array();
        $title = array();

        $classInstance = null;

        $serverName = Wax_Request::getInstance()->getServer('SERVER_NAME');
        $serverName = explode('.', $serverName);

        if ( $serverName[0] != 'standard8' )
            $currentModule = ucfirst($serverName[0]);
        else
            $currentModule = $this->_moduleDefault;

        if ( strlen($this->_moduleBase) > 0 && strlen($this->_classBase) > 0 )
        {
            $classBase = $this->instanceClass(
                null, array($this->_moduleBase, $this->_classBase)
            );
        }

        if ( ( strlen($this->_classForce) == 0 || sizeof($this->_classForce) == 0 ) && is_array($requestURI) && sizeof($requestURI) > 0 )
        {
            foreach ( $requestURI as $key => $value )
                if ( strlen($value) == 0 )
                    unset($requestURI[$key]);

            for ( $i = sizeof($requestURI); $i > 0; $i-- )
            {
                $continue = true;

                $filePath = str_replace(' ', '/', ucwords( implode(' ', $requestURI) ) );
                $fileName = $this->_binaryPath
                          . '' . $currentModule
                          . '/' . $filePath
                          . '.php';
                if ( file_exists($fileName) )
                {
                    if ( include_once $fileName )
                    {
                        $className = implode('_', $requestURI);

                        if ( class_exists($className) )
                        {
                            if ( $classBase )
                            {
                                $classReflection = new ReflectionClass($className);
                                $classConstructor = $classReflection->getConstructor();
                                $classConstructorParams = $classConstructor->getParameters();

                                if ( sizeof($classConstructorParams) == 0 )
                                    $continue = false;
                                elseif ( isset($classConstructorParams[0]) )
                                    if ( $classConstructorParams[0]->getClass()->name != $this->_classBase )
                                        $continue = false;
                            }

                            if ( $continue )
                            {
                                $title[] = implode(' :: ', $requestURI);

                                $classParam = array($currentModule, $className);
                                $classInstance = $this->instanceClass($classBase, $classParam, $parameters);

                                if ( $classInstance === true )
                                    return true;
                                elseif ( $classInstance === false )
                                    $classInstance = null;
                            }
                        }
                    }
                }

                array_unshift($parameters, array_pop($requestURI));
            }
        }

        # Wax_Document::$head->setTitle($title, ' :: ');

        if ( strlen($this->_moduleBase) > 0 && strlen($this->_classBase) > 0 )
        {
            if ( $classBase )
            {
                if ( strlen($this->_moduleForce) > 0 && strlen($this->_classForce) > 0 )
                {
                    $classForce = $this->instanceClass( $classBase, array($this->_moduleForce, $this->_classForce) );

                    if ( $classForce )
                    {
                        if ( method_exists($classBase, 'appendContent') )
                            $classBase->appendContent($classForce);
                        else
                            $classBase->appendChild($classForce);
                    }
                }
                elseif ( isset($classInstance) && !( is_null($classInstance) ) )
                {
                    if ( method_exists($classBase, 'appendContent') )
                        $classBase->appendContent($classInstance);
                    else
                        $classBase->appendChild($classInstance);
                }
                elseif ( strlen($this->_moduleDefault) > 0 && strlen($this->_classDefault) > 0 )
                {
                    $classDefault = $this->instanceClass( $classBase, array($this->_moduleDefault, $this->_classDefault) );

                    if ( $classDefault )
                    {
                        if ( method_exists($classBase, 'appendContent') )
                            $classBase->appendContent($classDefault);
                        else
                            $classBase->appendChild($classDefault);
                    }
                }

                Wax_Document::$body->appendChild($classBase);
            }
        }
        elseif ( isset($classInstance) && !( is_null($classInstance) ) )
            Wax_Document::$body->appendChild($classInstance);

        Wax_Response::getInstance()
            ->appendBody( Wax_Document::__toString() )
            ->sendResponse();

        return true;
    }
}