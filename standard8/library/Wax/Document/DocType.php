<?php

include_once 'Wax/Document/Element.php';

final class Wax_Document_DocType extends Wax_Document_Element
{
    private $_publicId = '-//W3C//DTD XHTML 1.1//EN';
    private $_systemId = 'http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd';

    public function __construct( $publicId = null, $systemId = null )
    {
        parent::__construct('Html');

        if ( $publicId ) $this->_publicId = $publicId;
        if ( $systemId ) $this->_systemId = $systemId;
    }

    public function __toString()
    {
        return '<!DOCTYPE ' . strtolower($this->tagName)
             . ' PUBLIC "' . $this->_publicId . '"'
             . ' "' . $this->_systemId . '">';
    }
}