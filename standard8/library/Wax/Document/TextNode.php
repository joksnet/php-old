<?php

include_once 'Wax/Document/Node.php';

class Wax_Document_TextNode extends Wax_Document_Node
{
    protected $_text = '';

    public function __construct( $text = '' )
    {
        $this->_text = $text;
    }

    public function __toString()
    {
        $xhtml = '';

        if ( strlen($this->_text) > 0 )
            $xhtml = $this->_text;

        return sprintf('%s'
          , $xhtml
        );
    }
}