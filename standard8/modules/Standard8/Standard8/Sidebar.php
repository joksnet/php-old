<?php

class Standard8_Sidebar extends Wax_Document_Body_Element
{
    /**
     * @var Wax_Document_Element
     */
    protected $_pagesnav = null;

    public function __construct()
    {
        parent::__construct('div');

        $this->setClassName('sidebar')
             ->setTagType( Wax_Document_Element::TAG_OPEN )
             ->isContainer(true);

        $this->_pagesnav = Wax_Document::createElement('ul', $this)
             ->setClassName('nav')
             ->isContainer(true);
    }

    /**
     * @return Standard8_Sidebar
     */
    public function appendNav( $label, $uri, $selected = false, $icon = null )
    {
        $li = Wax_Document::createElement('li');

        if ( $selected )
        {
            $li->setClassName('selected');
            $a = Wax_Document::createElement('span', $li);
        }
        else
            $a = Wax_Document::createElement('a', $li)
               ->setAttribute('href', $uri );

        if ( !( empty($icon) ) )
        {
            $a->appendChild(
                Wax_Document::$body->createImage(
                    Standard8_Uri::createUriIcon($icon)
                  , $label
                )
            );
        }

        $a->appendChild( Wax_Document::createTextNode($label) );
        $this->_pagesnav->appendChild($li);

        return $this;
    }

    /**
     * @return Standard8_Sidebar
     */
    public function appendLine( $label = null )
    {
        $li = Wax_Document::createElement('li')
            ->setClassName('line');

        if ( !( empty($label) ) )
            $li->appendChild( Wax_Document::createElement('span')->innerHTML($label) );
        else
            $li->appendChild( Wax_Document::createComment('') );

        $this->_pagesnav->appendChild($li);
    }
}