<?php

include_once 'Wax/Document/Body/Form/Element/Input.php';

class Wax_Document_Body_Form_Element_Input_Checkbox extends Wax_Document_Body_Form_Element_Input
{
    public function __construct( $name, $value = null, $options = array(1,0) )
    {
        parent::__construct('Checkbox', $name);

        if ( empty($options) )
            $options = array(1, 0);

        list($checked, $unchecked) = $options;

        if ( $value == $checked )
            $this->setAttribute('checked', 'checked');

        $this->setAttribute('value', $checked);
    }
}