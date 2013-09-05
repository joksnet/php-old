<?php

include_once 'Wax/Factory.php';
include_once 'Wax/Document/Exception.php';
include_once 'Wax/Document/Comment.php';
include_once 'Wax/Document/Element.php';
include_once 'Wax/Document/TextNode.php';
include_once 'Wax/Document/DocType.php';
include_once 'Wax/Document/Head.php';
include_once 'Wax/Document/Body.php';

class Wax_Document
{
    /**
     * @var Wax_Document_DocType
     */
    public static $docType = null;

    /**
     * @var Wax_Document_Element
     */
    public static $documentElement = null;

    /**
     * @var Wax_Document_Head
     */
    public static $head = null;

    /**
     * @var Wax_Document_Body
     */
    public static $body = null;

    public static function __init()
    {
        self::$docType = Wax_Factory::createObject('Wax_Document_DocType');
        self::$documentElement = self::createElement('html')
            ->setAttribute('xmlns', 'http://www.w3.org/1999/xhtml');

        self::$head = Wax_Factory::createObject('Wax_Document_Head');
        self::$body = Wax_Factory::createObject('Wax_Document_Body');

        self::$documentElement->appendChild(self::$head);
        self::$documentElement->appendChild(self::$body);
    }

    /**
     * @return Wax_Document_Element
     */
    public static function createElement( $tagName, $parent = null, $extras = null )
    {
        $newElement = Wax_Document_Element::createElement($tagName);

        if ( $parent instanceof Wax_Document_Element )
            $parent->appendChild($newElement);
        elseif ( is_string($parent) )
        {
            $parent = self::getElementById($parent);
            $parent->appendChild($newElement);
        }

        return $newElement->setAttributes($extras);
    }

    /**
     * @return Wax_Document_Comment
     */
    public static function createComment( $comment, $parent = null )
    {
        $newElement = Wax_Factory::createObject('Wax_Document_Comment', $comment);

        if ( $parent instanceof Wax_Document_Element )
            $parent->appendChild($newElement);
        elseif ( is_string($parent) )
        {
            $parent = self::getElementById($parent);
            $parent->appendChild($newElement);
        }

        return $newElement;
    }

    /**
     * @return Wax_Document_TextNode
     */
    public static function createTextNode( $text, $parent = null )
    {
        $newElement = Wax_Factory::createObject('Wax_Document_TextNode', $text);

        if ( $parent instanceof Wax_Document_Element )
            $parent->appendChild($newElement);
        elseif ( is_string($parent) )
        {
            $parent = self::getElementById($parent);
            $parent->appendChild($newElement);
        }

        return $newElement;
    }

    /**
     * @return Wax_Document_Element
     */
    public static function getElementById( $id )
    {
        return self::$body->getElementById($id);
    }

    public static function getElementsByTagName( $tagName )
    {
        return self::$body->getElementsByTagName($tagName);
    }

    public static function __toString()
    {
        return sprintf('%s%s'
            , self::$docType->__toString()
            , self::$documentElement->__toString()
        );
    }
}

Wax_Document::__init();