<?php

include_once 'Wax/Document/Body/Element/Exception.php';

class Wax_Document_Body_Element extends Wax_Document_Element
{
    public function __construct( $tagName = null )
    {
        parent::__construct($tagName);

        # if ( !( is_null($id) ) && strlen($id) > 0 )
        #     $this->setId($id);
        # else
        # {
        #     $classExplode = explode('_', get_class($this));
        #     $this->setId( array_pop($classExplode) );
        # }
    }

    final public function setId( $id )
    {
        if ( Wax_Document::getElementById($id) )
        {
            throw new Wax_Document_Body_Element_Exception(
                "Id \"$id\" already exists"
            );
        }

        parent::setId($id);
    }
}