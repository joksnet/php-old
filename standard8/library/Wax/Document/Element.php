<?php

include_once 'Wax/Factory.php';
include_once 'Wax/Document/Node.php';
include_once 'Wax/Document/Element/Exception.php';
include_once 'Wax/Document/Element/Attributes.php';
include_once 'Wax/Document/Element/Attributes/Style.php';

class Wax_Document_Element extends Wax_Document_Node implements IteratorAggregate
{
    const TAG_AUTO = 0;
    const TAG_OPEN = 1;
    const TAG_CLOSE = 2;

    /**
     * @var Wax_Document_Element_Attributes
     */
    public $attributes = null;

    /**
     * @var Wax_Document_Element_Attributes_Style
     */
    public $style = null;

    public $id = null;
    public $tagName = null;
    public $className = null;

    protected $_tagType = self::TAG_AUTO;
    protected $_innerHTML = null;
    protected $_childNodes = null;
    protected $_isContainer = null;

    /**
     * @return Wax_Document_Element
     */
    public static function createElement( $tagName )
    {
        return Wax_Factory::createObject('Wax_Document_Element', $tagName);
    }

    public function __construct( $tagName = null )
    {
        $this->attributes = Wax_Factory::createObject('Wax_Document_Element_Attributes');
        $this->style = Wax_Factory::createObject('Wax_Document_Element_Attributes_Style');

        $this->id = '';
        $this->tagName = ( $tagName ) ? $tagName : '';
        $this->className = '';

        $this->_innerHTML = '';
        $this->_childNodes = array();
    }

    /**
     * @return Wax_Document_Element
     */
    public function getElementById( $id )
    {
        foreach ( (array) $this->_childNodes as $currentChild )
        {
            if ( $currentChild->id == $id )
                return $currentChild;
        }

        return null;
    }

    public function getElementsByTagName( $tagName )
    {
        $elements = array();

        foreach ( (array) $this->_childNodes as $currentChild )
        {
            if ( $currentChild->tagName == $tagName )
                array_push($elements, $currentChild);
        }

        return $elements;
    }

    public function getElementsByClassName( $className )
    {
        $elements = array();

        foreach ( (array) $this->_childNodes as $currentChild )
        {
            if ( $currentChild->className == $className )
                array_push($elements, $currentChild);
        }

        return $elements;
    }

    public function hasElement( $id )
    {
        return ( $this->getElementById($id) !== null );
    }

    /**
     * @return Wax_Document_Element
     */
    public function setAttribute( $name, $value )
    {
        if ( is_null($value) )
            return $this;

        if ( strlen($name) == 0 )
        {
            throw new Wax_Document_Element_Exception(
                'Attribute name must be a non-empty string'
            );
        }

        $this->attributes
             ->setAttribute($name, $value);

        return $this;
    }

    /**
     * @return Wax_Document_Element
     */
    public function setAttributes( $extras )
    {
        if ( is_array($extras) )
            foreach ( (array) $extras as $key => $value )
                $this->setAttribute($key, $value);

        return $this;
    }

    public function getAttribute( $name ) { return $this->attributes->getAttribute($name); }
    public function hasAttribute( $name ) { return $this->attributes->hasAttribute($name); }

    /**
     * @return Wax_Document_Element
     */
    public function removeAttribute( $name )
    {
        $this->attributes
             ->removeAttribute($name);

        return $this;
    }

    /**
     * @return Wax_Document_Element
     */
    public function setStyle( $style, $value )
    {
        if ( is_null($style) )
            return $this;

        if ( strlen($style) == 0 )
        {
            throw new Wax_Document_Element_Exception(
                'Style name must be a non-empty string'
            );
        }

        if ( empty($value) )
            return $this;

        $this->style
             ->setAttribute($style, $value);

        return $this;
    }

    public function getStyle( $style ) { return $this->style->getAttribute($style); }
    public function hasStyle( $style ) { return $this->style->hasAttribute($style); }

    /**
     * @return Wax_Document_Element
     */
    public function removeStyle( $style )
    {
        $this->style
             ->removeAttribute($style);

        return $this;
    }

    protected function getChildPosition( $child )
    {
        if ( $child instanceof Wax_Document_Node )
        {
            foreach ( (array) $this->_childNodes as $key => $currentChild )
            {
                if ( $currentChild === $child )
                {
                    return $key;
                }
            }
        }
        elseif ( is_int($child) )
            return $child;

        return -1;
    }

    /**
     * @return Wax_Document_Element
     */
    public function setTagType( $tagType )
    {
        switch ( $tagType )
        {
            case self::TAG_AUTO:
                $this->_tagType = self::TAG_AUTO;
                break;
            case self::TAG_OPEN:
                $this->_tagType = self::TAG_OPEN;
                break;
            case self::TAG_CLOSE:
                $this->_tagType = self::TAG_CLOSE;
                break;
        }

        return $this;
    }

    /**
     * @return Wax_Document_Element
     */
    public function innerHTML( $xhtml )
    {
        if ( is_null($xhtml) )
            return $this;

        foreach ( (array) $this->_childNodes as $key => $currentChild )
            $this->removeChild($key);

        $this->_innerHTML = $xhtml;

        return $this;
    }

    /**
     * @return Wax_Document_Element
     */
    public function setId( $id )
    {
        $this->id = $id;
        $this->setAttribute('id', $id);

        return $this;
    }

    /**
     * @return Wax_Document_Element
     */
    public function removeId()
    {
        $this->id = '';
        $this->removeAttribute('id');

        return $this;
    }

    /**
     * @return Wax_Document_Element
     */
    public function setClassName( $className )
    {
        $this->className = $className;
        $this->setAttribute('class', $className);

        return $this;
    }

    /**
     * @return Wax_Document_Element
     */
    public function appendClassName( $className )
    {
        if ( strlen($this->className) == 0 )
        {
            $this->className = $className;
            $this->setAttribute('class', $className);
        }
        elseif ( strpos($this->className, $className) === false )
        {
            $this->className .= ' ' . $className;
            $this->setAttribute('class', $this->className);
        }

        return $this;
    }

    /**
     * @return Wax_Document_Element
     */
    public function addClass( $className ) { return $this->appendClassName($className); }

    /**
     * @return Wax_Document_Element
     */
    public function removeClassName( $className )
    {
        settype($className, 'array');

        if ( strlen($this->className) == 0 )
        {
            $classesNew = array();
            $classes = explode(' ', $this->className);

            foreach ( $classes as $class )
                if ( !( in_array($class, $className) ) )
                    $classesNew[] = $class;

            $this->setClassName( implode(' ', $classesNew) );
        }

        return $this;
    }

    /**
     * @return Wax_Document_Element
     */
    public function isContainer( $isContainer = null )
    {
        if ( is_null($isContainer) )
            return $this->_isContainer;

        settype($isContainer, 'boolean');

        if ( $isContainer )
            $this->setTagType( self::TAG_OPEN );
        else
            $this->setTagType( self::TAG_CLOSE );

        $this->_isContainer = $isContainer;

        return $this;
    }

    /**
     * @return Wax_Document_Element
     */
    public function appendChild( $child )
    {
        if ( is_null($child) )
            return $this;

        if ( !( $child instanceof Wax_Document_Node ) )
        {
            # throw new Wax_Document_Element_Exception(
            #     'The child must be a instance of Wax_Document_Element'
            # );

            return $this;
        }

        $position = sizeof($this->_childNodes);
        $this->insertBefore($child, $position);

        return $this;
    }

    /**
     * @return Wax_Document_Element
     */
    public function removeChild( $child )
    {
        $position = -1;

        if ( $child instanceof Wax_Document_Node )
            $position = $this->getChildPosition($child);
        elseif ( is_int($child) )
            $position = $child;

        if ( $position >= 0 )
            unset($this->_childNodes[$position]);

        return $this;
    }

    /**
     * @return Wax_Document_Element
     */
    public function insertBefore( $child, $brother )
    {
        $position = $this->getChildPosition($brother);

        if ( $position >= 0 )
        {
            if ( array_key_exists($position, $this->_childNodes) || isset($this->_childNodes[$position]) )
                $this->insertBefore($this->_childNodes[$position], $position + 1);

            $this->_childNodes[$position] = $child;
        }

        return $this;
    }

    /**
     * @return Wax_Document_Element
     */
    public function replaceChild( $child, $replace )
    {
        $position = $this->getChildPosition($replace);

        if ( $position >= 0 )
        {
            $this->removeChild($replace);
            $this->insertBefore($child, $position);
        }

        return $this;
    }

    public function getIterator()
    {
        return new ArrayObject($this->_childNodes);
    }

    public function __toString()
    {
        $xhtml = '';

        if ( $this->_isContainer && empty($this->_childNodes) )
            return '';

        foreach ( $this->_childNodes as $child )
            $xhtml .= $child->__toString();

        $tagAuto = $this->_tagType == self::TAG_AUTO;
        $tagOpen = $this->_tagType == self::TAG_OPEN;
        $tagClose = $this->_tagType == self::TAG_CLOSE;

        $empty = !( strlen($xhtml) > 0 || strlen($this->_innerHTML) > 0 );

        if ( $tagOpen || ( $tagAuto && !( $empty ) ) )
            return sprintf('<%s%s%s>%s%s</%s>'
                , strtolower($this->tagName)
                , $this->attributes->__toString()
                , $this->style->__toString()
                , $this->_innerHTML
                , $xhtml
                , strtolower($this->tagName)
            );
        else
            return sprintf('<%s%s%s />'
                , strtolower($this->tagName)
                , $this->attributes->__toString()
                , $this->style->__toString()
            );
    }
}