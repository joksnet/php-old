<?php

class Standard8_Location extends Wax_Document_Body_Element
{
    /**
     * @var Wax_Document_Element
     */
    protected $_lastLi = null;

    public function __construct()
    {
        parent::__construct('ul');

        $this->appendLocation( __('Principal'), Standard8_Uri::createUri(), false, 'house' );

        # $this->_lastLi->appendClassName('drop');
        # $this->_lastLi->appendChild(
        #     Wax_Factory::createObject( array('Standard8', 'Standard8_Location_Modules') )
        # );
    }

    /**
     * @return Standard8_Location
     */
    public function appendLocation( $label, $uri, $inside = false, $icon = null )
    {
        $li = Wax_Document::createElement('li');
        $a = Wax_Document::createElement('a', $li)
           ->setAttribute('href', $uri );

        if ( !( empty($icon) ) )
        {
            $li->appendClassName('image');
            $a->appendChild( Wax_Document::createElement('em')
                ->appendChild(
                    Wax_Document::$body->createImage(
                        Standard8_Uri::createUriIcon($icon)
                      , $label
                    ) )
                ->appendChild( Wax_Document::createTextNode($label) )
            );
        }
        else
        {
            $a->appendChild(
                Wax_Document::createElement('em')->innerHTML($label)
            );
        }

        if ( !( is_null($this->_lastLi) ) && $inside )
        {
            $this->_lastLi->appendClassName('drop');
            $ul = $this->_lastLi->getElementsByTagName('ul');

            if ( sizeof($ul) == 0 )
            {
                $ul = Wax_Document::createElement('ul', $this->_lastLi)
                    ->appendChild($li);
            }
            elseif ( sizeof($ul) == 1 )
            {
                $ul = array_shift($ul);

                if ( $ul instanceof Wax_Document_Element )
                    $ul->appendChild($li);
            }
        }
        else
        {
            $this->_lastLi = $li;
            $this->appendChild($li);
        }

        return $this;
    }
}