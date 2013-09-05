<?php

include_once 'Wax/Document/Comment.php';
include_once 'Wax/Document/Head/Exception.php';

final class Wax_Document_Head_Condition extends Wax_Document_Comment
{
    protected $_condition = null;
    protected $_content = null;

    public function __construct( $condition = null, $content = null )
    {
        settype($condition, 'string');

        $this->_condition = $condition;
        $this->_content = $content;

        parent::__construct();
    }

    public function __toString()
    {
        if ( $this->_content instanceof Wax_Document_Element )
            $content = $this->_content->__toString();
        else
            $content = $this->_content;

        $comment = sprintf('[if %s]>%s<![endif]'
            , $this->_condition
            , $content
        );

        $this->setComment($comment);

        return parent::__toString();
    }
}