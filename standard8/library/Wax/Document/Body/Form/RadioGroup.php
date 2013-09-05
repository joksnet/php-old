<?php

include_once 'Wax/Factory.php';
include_once 'Wax/Document/Body/Element.php';
include_once 'Wax/Document/Body/Form/Element/Input.php';
include_once 'Wax/Document/Body/Form/Element/Input/Radio.php';

class Wax_Document_Body_Form_RadioGroup extends Wax_Document_Body_Element
{
    public function __construct( $options, $name = null, $value = null, $listsep = null )
    {
        parent::__construct('Div');

        if ( empty($listsep) )
            $listsep = Wax_Document::createTextNode('&nbsp;');
        elseif ( is_string($listsep) )
            $listsep = Wax_Document::createElement($listsep);

        $i = 0;

        foreach ( (array) $options as $radioValue => $radioLabel )
        {
            $radioId = $name . ucfirst($radioValue);
            $radio = Wax_Factory::createObject('Wax_Document_Body_Form_Element_Input_Radio'
                , $name, $radioValue);

            if ( $value == $radioValue )
                $radio->setAttribute('checked', 'checked');

            $radio->setId($radioId);

            $this->appendChild($radio)
                 ->appendChild(
                    Wax_Document::createTextNode('&nbsp;')
                 )
                 ->appendChild(
                    Wax_Document::createElement('label')
                        ->innerHTML($radioLabel)
                        ->setAttribute('for', $radioId)
                 )
                 ->appendChild($listsep);

            $i++;
        }
    }
}