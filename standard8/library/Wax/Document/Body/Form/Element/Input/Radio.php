<?php

include_once 'Wax/Document/Body/Form/Element/Input.php';

class Wax_Document_Body_Form_Element_Input_Radio extends Wax_Document_Body_Form_Element_Input
{
    public function __construct( $name, $value = null )
    {
        parent::__construct('Radio', $name);
        $this->setAttribute('value', $value);
    }
}