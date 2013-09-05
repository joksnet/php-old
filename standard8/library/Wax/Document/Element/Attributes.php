<?php

include_once 'Wax/Document/Element/Attributes/Exception.php';

class Wax_Document_Element_Attributes implements IteratorAggregate
{
    protected $_attributes = array();

    public function setAttribute( $name, $value )
    {
        if ( strlen($name) == 0 )
        {
            throw new Wax_Document_Element_Attributes_Exception(
                "The key must be a non-empty string"
            );
        }

        $this->_attributes[$name] = $value;
    }

    public function getAttribute( $name )
    {
        if ( strlen($name) == 0 )
        {
            throw new Wax_Document_Element_Attributes_Exception(
                "The key must be a non-empty string"
            );
        }

        return $this->_attributes[$name];
    }

    public function hasAttribute( $name )
    {
        if ( strlen($name) == 0 )
        {
            throw new Wax_Document_Element_Attributes_Exception(
                "The key must be a non-empty string"
            );
        }

        return isset($this->_attributes[$name]);
    }

    public function removeAttribute( $name )
    {
        if ( strlen($name) == 0 )
        {
            throw new Wax_Document_Element_Attributes_Exception(
                "The key must be a non-empty string"
            );
        }

        unset($this->_attributes[$name]);
    }

    public function &__get( $name ) { return $this->getAttribute($name); }
    public function __set( $name, $value ) { $this->setAttribute($name, $value); }
    public function __isset( $name ) { return $this->hasAttribute($name); }
    public function __unset( $name ) { $this->removeAttribute($name); }

    public function getIterator()
    {
        return new ArrayObject($this->_attributes);
    }

    public function __toString()
    {
        $xhtml = '';

        foreach ( (array) $this->_attributes as $key => $value )
        {
            $key = strtolower($key);
            $key = htmlspecialchars($key, ENT_COMPAT, 'UTF-8');

            if ( is_array($value) )
                $value = implode(' ', $value);

            $value = htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
            $xhtml .= sprintf(' %s="%s"'
                , $key
                , $value
            );
        }

        return $xhtml;
    }
}