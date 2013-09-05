<?php

include_once 'Wax/Document/Element.php';

class Wax_Document_Body_Image extends Wax_Document_Body_Element
{
    public function __construct( $src = null, $alt = null )
    {
        parent::__construct('img');

        $this->setTagType( Wax_Document_Element::TAG_CLOSE )
             ->setSource($src)
             ->setAlt($alt);
    }

    /**
     * @return Wax_Document_Body_Image
     */
    public function setSource( $src )
    {
        settype($src, 'string');

        if ( strlen($src) > 0 )
            $this->setAttribute('src', $src);

        return $this;
    }

    /**
     * @return Wax_Document_Body_Image
     */
    public function setAlt( $alt )
    {
        settype($alt, 'string');

        if ( strlen($alt) > 0 )
            $this->setAttribute('alt', $alt);

        return $this;
    }
}