<?php

include_once 'Wax/Document/Body/Form/Element/Input.php';

class Wax_Document_Body_Form_Element_Input_Password extends Wax_Document_Body_Form_Element_Input
{
    public function __construct( $name )
    {
        parent::__construct('Password', $name);
    }
}