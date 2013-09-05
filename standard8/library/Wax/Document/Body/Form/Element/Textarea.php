<?php

include_once 'Wax/Document/Body/Form/Element.php';

class Wax_Document_Body_Form_Element_Textarea extends Wax_Document_Body_Form_Element
{
    private $_rows = 12;
    private $_cols = 60;

    public function __construct( $name, $value = null, $rows = null, $cols = null )
    {
        parent::__construct('Textarea', $name);

        if ( empty($rows) )
            $rows = $this->_rows;

        if ( empty($cols) )
            $cols = $this->_cols;

        $this->setAttribute('rows', $rows)
             ->setAttribute('cols', $cols)
             ->setTagType( Wax_Document_Element::TAG_OPEN )
             ->innerHTML($value);
    }
}