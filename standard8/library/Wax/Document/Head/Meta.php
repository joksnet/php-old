<?php

include_once 'Wax/Document/Element.php';

final class Wax_Document_Head_Meta extends Wax_Document_Element
{
    private $_name = null;
    private $_httpEquiv = null;
    private $_content = null;

    public function __construct( $content, $name = null, $httpEquiv = null )
    {
        parent::__construct('Meta');

        $this->_name = $name;
        $this->_httpEquiv = $httpEquiv;
        $this->_content = $content;
    }

    public function __toString()
    {
        if ( $this->_name )
            $this->setAttribute('name', $this->_name);

        if ( $this->_httpEquiv )
            $this->setAttribute('http-equiv', $this->_httpEquiv);

        $this->setAttribute('content', $this->_content);

        return parent::__toString();
    }
}