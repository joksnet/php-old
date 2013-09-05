<?php

class Standard8 extends Wax_Document_Body_Element
{
    /**
     * @var Wax_Document_Element
     */
    protected $_locationDiv = null;

    /**
     * @var Standard8_Location
     */
    protected $_locationBar = null;

    /**
     * @var Wax_Document_Element
     */
    protected $_locationLogout = null;

    /**
     * @var Standard8_Sidebar
     */
    protected $_sidebar = null;

    /**
     * @var Wax_Document_Element
     */
    protected $_container = null;

    /**
     * @var Wax_Document_Element
     */
    protected $_bottom = null;

    public function __construct()
    {
        parent::__construct('div');

        $this->_locationBar = Wax_Factory::createObject(
            array('Standard8', 'Standard8_Location')
        );

        if ( Standard8_Session::getSID() )
        {
            $username = Standard8_Session::getData('usuario');

            $this->_locationLogout = Wax_Document::createElement('div')
                 ->setClassName('logout')
                 ->appendChild(
                    Wax_Document::createElement('a')
                        ->appendChild(
                            Wax_Document::$body->createImage(
                                Standard8_Uri::createUriIcon('user'), $username
                            )
                        )->appendChild(
                            Wax_Document::createTextNode($username)
                        )->setAttribute('href', Standard8_Uri::createUriProfile($username))
                 )->appendChild( Wax_Document::createTextNode(' - ')
                 )->appendChild(
                    Wax_Document::createElement('a')
                        ->setAttribute('href', Standard8_Uri::createUri('Personas_Salir', 'Standard8'))
                        ->innerHTML( __('Salir') )
                 );
        }

        $this->_locationDiv = Wax_Document::createElement('div', $this)
             ->setClassName('location')
             ->appendChild( Wax_Document::createElement('strong')->innerHTML('Standard8') )
             ->appendChild($this->_locationBar)
             ->appendChild($this->_locationLogout);

        $this->_sidebar = Wax_Factory::createObject(
            array('Standard8', 'Standard8_Sidebar')
        );

        $this->appendChild($this->_sidebar);

        $SID = Standard8_Session::getSID();

        if ( !( empty($SID) ) )
        {
            $this->appendLocation( __('Administracion'), '', 'Administracion' )
                 ->appendLocation( __('Configuracion'), 'Configuracion', 'Administracion', true, 'cog' )
                 ->appendLocation( __('Personas'), 'Personas', 'Administracion', true, 'user' );
        }

        $this->_container = Wax_Document::createElement('div', $this)
             ->setClassName('container');

        $this->_bottom = Wax_Document::createElement('div')
             ->setClassName('bottom')
             ->appendChild( Wax_Document::createComment(' ') );
    }

    protected function getNameByInstance( $className, $moduleName )
    {
        if ( $className instanceof Wax_Document_Node )
        {
            $classInstance = $className;
            $className = get_class($classInstance);
            $classReflection = new ReflectionClass($className);
            $classPath = $classReflection->getFileName();

            $moduleName = $classPath;
            $moduleName = str_replace('/home/vhosts/standard8.com.ar/modules/', '', $moduleName);
            $moduleName = str_replace('/' . str_replace(' ', '/', ucwords( str_replace('_', ' ', $className) ) ) . '.php', '', $moduleName );
        }

        return array($className, $moduleName);
    }

    /**
     * @return Standard8
     */
    public function appendSidebarNav( $label, $className = null, $moduleName = null, $selected = false, $icon = null )
    {
        list($className, $moduleName) = $this->getNameByInstance($className, $moduleName);

        $this->_sidebar->appendNav(
            $label, Standard8_Uri::createUri($className, $moduleName), $selected, $icon
        );

        return $this;
    }

    /**
     * @return Standard8
     */
    public function appendSidebarLine( $label = null )
    {
        $this->_sidebar
             ->appendLine($label);

        return $this;
    }

    /**
     * @return Standard8
     */
    public function appendLocation( $label, $className = null, $moduleName = null, $inside = false, $icon = null )
    {
        list($className, $moduleName) = $this->getNameByInstance($className, $moduleName);

        $this->_locationBar->appendLocation(
            $label, Standard8_Uri::createUri($className, $moduleName), $inside, $icon
        );

        return $this;
    }

    /**
     * @return Standard8
     */
    public function appendContent( $child )
    {
        if ( $child instanceof Wax_Document_Node )
            $this->_container->appendChild($child);

        return $this;
    }

    /**
     * @return Standard8_TitleNav
     */
    public function createTitleNav( $title, $nav = null )
    {
        return Wax_Factory::createObject(
            array('Standard8', 'Standard8_TitleNav')
          , $title
          , $nav
        );
    }

    public function __toString()
    {
        $this->_container->appendChild(
            $this->_bottom
        );

        return parent::__toString();
    }
}