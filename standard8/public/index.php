<?php

error_reporting(E_ALL | E_STRICT);

$rootPath = '../';

include_once $rootPath . 'includes/config.php';
include_once $rootPath . 'includes/constants.php';

set_include_path(get_include_path()
    . PATH_SEPARATOR . LIBRARY_PATH
    . PATH_SEPARATOR . MODULES_PATH
);

include_once 'Wax/Factory.php';

Wax_Factory::includeClass('Wax_Request');
Wax_Factory::includeClass('Wax_Response');

$requestURI = Wax_Request::getInstance()->getRequestUri();

if ( strncmp($requestURI, '/@@/', 4) == 0 )
{
    Wax_Factory::includeClass('Wax_Controller_Icon');

    Wax_Controller_Icon::getInstance()
        ->setIconPath(ICON_PATH)
        ->dispatch();
}
elseif ( strncmp($requestURI, '/@JavaScript/', 5) == 0 )
{
    Wax_Factory::includeClass('Wax_Controller_JavaScript');

    Wax_Controller_JavaScript::getInstance()
        ->setModulesPath(MODULES_PATH)
        ->setLibraryPath(LIBRARY_PATH)
        ->dispatch();
}
elseif ( strncmp($requestURI, '/~', 2) == 0 )
{
    Wax_Factory::includeClass('Wax_Controller_Profile');

    Wax_Controller_Profile::getInstance()
        ->setProfileClass( array('Standard8', 'Personas_Perfil') )
        ->dispatch();
}
else
{
    Wax_Factory::includeClass('Wax_Controller_Front');
    Wax_Factory::includeClass('Wax_Session');
    Wax_Factory::includeClass('Wax_Db');

    Wax_Factory::includeClass('Standard8_Config');
    Wax_Factory::includeClass('Standard8_Session');
    Wax_Factory::includeClass('Standard8_Uri');

    Wax_Session::start();

    Wax_Locale::setLocalePath(LOCALE_PATH);
    Wax_Locale::__init();

    Wax_Db::open($dbConfig);

    Standard8_Session::start();
    Standard8_Config::getInstance();

    Wax_Document::$head->importStyle('/stylesheets/screen.css');
    Wax_Document::$head->importStyle('/stylesheets/ie.css', Wax_Document_Head_Style::MEDIA_SCREEN, 'IE 6');

    Wax_Document::$head->importJavaScript('/javascripts/jquery.js');
    Wax_Document::$head->importJavaScript('/javascripts/wax.js');

    $SID = Standard8_Session::getSID();
    $frontController = Wax_Controller_Front::getInstance()
                     ->setBinaryPath(MODULES_PATH)
                     ->setBase( array('Standard8', 'Standard8') )
                     ->setDefault( array('Standard8', 'Modules') );

    if ( empty( $SID ) )
    {
        $currentClass = $frontController->getClass();
        $supportClasses = array('Personas_Invitacion', 'Personas_Recuperar', 'Personas_Ingresar');
        $supportFound = false;

        foreach ( $supportClasses as $supportClass )
        {
            $supportClass = strtolower($supportClass);

            if ( $supportFound = ( substr( strtolower($currentClass[1]), 0, strlen($supportClass) ) == $supportClass ) )
                break;
        }

        # var_dump($supportFound);

        if ( in_array('Standard8', $currentClass) && $supportFound )
            $frontController->setForce( $currentClass );
        else
            $frontController->setForce( array('Standard8', 'Personas_Ingresar') );
    }

    $frontController
        ->dispatch();

    Wax_Db::close();
}