<?php

include_once 'Wax/Document/Body/Form/Element.php';

class Wax_Document_Body_Form_Element_Input extends Wax_Document_Body_Form_Element
{
    public function __construct( $type, $name, $keys = null )
    {
        parent::__construct('Input', $name, $keys);
        $this->setAttribute('type', strtolower($type));
    }
}