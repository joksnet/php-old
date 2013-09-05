<?php

include_once 'Wax/Document/Body/Form/Element/Input.php';

class Wax_Document_Body_Form_Element_Input_Image extends Wax_Document_Body_Form_Element_Input
{
    public function __construct( $name, $src, $label = null )
    {
        parent::__construct('Image', $name);

        $this->setAttribute('src', $src);
        $this->setAttribute('alt', $label);
    }
}