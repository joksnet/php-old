<?php

include_once 'Wax/Document/Element/Attributes.php';

class Wax_Document_Element_Attributes_Style extends Wax_Document_Element_Attributes
{
    final public function __toString()
    {
        $xhtml = '';

        foreach ( (array) $this->_attributes as $key => $value )
        {
            $key = strtolower($key);
            $key = htmlspecialchars($key, ENT_COMPAT, 'UTF-8');

            $value = htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
            $xhtml .= sprintf(' %s: %s;'
                , $key
                , $value
            );
        }

        if ( strlen($xhtml) > 0 )
            return sprintf(' style="%s"'
                , ltrim($xhtml)
            );
        else
            return '';
    }
}