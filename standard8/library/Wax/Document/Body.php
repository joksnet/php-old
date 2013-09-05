<?php

include_once 'Wax/Factory.php';
include_once 'Wax/Document/Comment.php';
include_once 'Wax/Document/Element.php';
include_once 'Wax/Document/TextNode.php';
include_once 'Wax/Document/Body/Exception.php';
include_once 'Wax/Document/Body/Element.php';
include_once 'Wax/Document/Body/Form.php';
include_once 'Wax/Document/Body/Table.php';

final class Wax_Document_Body extends Wax_Document_Element
{
    public function __construct( $id = null )
    {
        parent::__construct('Body');

        if ( $id )
            $this->setId($id);
    }

    /**
     * @return Wax_Document_Body_Element
     */
    public static function createElement( $name )
    {
        $args = func_get_args();
        $name = array_shift($args);

        try {
            if ( sizeof($args) > 0 )
                $child = Wax_Factory::createObject('Wax_Document_Body_' . $name, $args);
            else
                $child = Wax_Factory::createObject('Wax_Document_Body_' . $name);
        }
        catch ( Exception $e ) {
            $child = false;

            throw new Wax_Document_Body_Exception(
                "Body element \"$name\" does not exists"
            );
        }

        # if ( $append && $child !== false )
        #     $this->appendChild($child);

        return $child;
    }

    /**
     * @return Wax_Document_Comment
     */
    public static function createComment( $comment )
    {
        return Wax_Factory::createObject('Wax_Document_Comment', $comment);
    }

    /**
     * @return Wax_Document_Body_Form
     */
    public function createForm( $action = '', $method = null )
    {
        # Wax_Document::$head->importJavaScript('/javascripts/jquery.metadata.js');
        # Wax_Document::$head->importJavaScript('/javascripts/jquery.validate.js');

        return self::createElement('Form', $action, $method);
    }

    /**
     * @return Wax_Document_Body_Image
     */
    public function createImage( $src = null, $alt = null )
    {
        return self::createElement('Image', $src, $alt);
    }

    /**
     * @return Wax_Document_Body_Table
     */
    public function createTable( $name = null, $type = Wax_Document_Body_Table::TYPE_NORMAL, $extras = null )
    {
        return self::createElement('Table', $name, $type, $extras);
    }
}
