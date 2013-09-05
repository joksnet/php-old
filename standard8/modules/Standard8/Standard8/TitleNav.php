<?php

class Standard8_TitleNav extends Wax_Document_Body_Element
{
    /**
     * @var Wax_Document_Element
     */
    protected $_h1 = null;

    /**
     * @var Wax_Document_Element
     */
    protected $_ul = null;

    public function __construct( $title = null, $nav = null, $default = null )
    {
        parent::__construct('div');

        settype($title, 'string');
        settype($nav, 'array');
        settype($default, 'array');

        $this->setClassName('title-nav');

        $this->_h1 = Wax_Document::createElement('h1', $this)
             ->innerHTML($title);

        if ( sizeof($nav) > 0 )
        {
            $this->_ul = Wax_Document::createElement('ul', $this);

            foreach ( $nav as $navLabel => $navUri )
                $this->appendNav( $navLabel, $navUri, in_array($navLabel, $default) );
        }

        $this->appendChild( Wax_Document::createElement('br') );
    }

    /**
     * @return Standard8_TitleNav
     */
    public function setTitle( $title )
    {
        if ( strlen($title) > 0 )
            $this->_h1->innerHTML($title);

        return $this;
    }

    /**
     * @return Standard8_TitleNav
     */
    public function appendNav( $label, $uri, $default = false, $icon = null )
    {
        if ( is_null($this->_ul) )
            $this->_ul = Wax_Document::createElement('ul', $this);

        if ( is_string($icon) )
            $icon = Wax_Document::$body->createImage(
                Standard8_Uri::createUriIcon($icon), $label
            );

        settype($default, 'boolean');

        if ( $default )
            Wax_Document::createElement('li', $this->_ul)
                ->setClassName('active')
                ->appendChild( Wax_Document::createElement('span')
                    ->appendChild( $icon )
                    ->appendChild( Wax_Document::createTextNode($label) )
                );
        else
            Wax_Document::createElement('li', $this->_ul)
                ->appendChild( Wax_Document::createElement('a')
                    ->setAttribute('href', $uri)
                    ->appendChild( Wax_Document::createElement('span')
                        ->appendChild( $icon )
                        ->appendChild( Wax_Document::createTextNode($label) ) )
                );

        return $this;
    }
}