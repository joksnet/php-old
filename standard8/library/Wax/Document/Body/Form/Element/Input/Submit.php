<?php

include_once 'Wax/Document/Body/Form/Element/Input.php';

class Wax_Document_Body_Form_Element_Input_Submit extends Wax_Document_Body_Form_Element_Input
{
    public function __construct( $name, $label )
    {
        parent::__construct('Submit', $name);
        $this->setAttribute('value', $label);
    }
}