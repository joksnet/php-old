<?php

include_once 'Wax/Document/Body/Element.php';

class Wax_Document_Body_Form_Select_OptionGroup extends Wax_Document_Body_Element
{
    public function __construct( $label )
    {
        parent::__construct('OptGroup');

        $this->setAttribute('label', $label);
        $this->removeId();
    }
}