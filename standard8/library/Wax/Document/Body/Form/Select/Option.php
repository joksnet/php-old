<?php

include_once 'Wax/Document/Body/Element.php';

class Wax_Document_Body_Form_Select_Option extends Wax_Document_Body_Element
{
    public function __construct( $value, $label, $selected = false )
    {
        parent::__construct('Option');

        $this->setAttribute('value', $value);
        $this->innerHTML($label);
        $this->removeId();

        if ( $selected )
            $this->setAttribute('selected', 'selected');
    }
}