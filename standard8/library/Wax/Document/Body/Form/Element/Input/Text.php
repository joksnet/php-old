<?php

include_once 'Wax/Document/Body/Form/Element/Input.php';

class Wax_Document_Body_Form_Element_Input_Text extends Wax_Document_Body_Form_Element_Input
{
    public function __construct( $name, $value )
    {
        parent::__construct('Text', $name);
        $this->setAttribute('value', $value);
    }
}