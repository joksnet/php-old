<?php

include_once 'Wax/Document/Node.php';

class Wax_Document_Comment extends Wax_Document_Node
{
    protected $_comment = '';

    public function __construct( $comment = '' )
    {
        $this->_comment = $comment;
    }

    /**
     * @return Wax_Document_Comment
     */
    public function setComment( $comment = null )
    {
        if ( is_null($comment) )
            $this->_comment = '';
        else
            $this->_comment = $comment;

        return $this;
    }

    public function getComment()
    {
        return $this->_comment;
    }

    public function __toString()
    {
        $xhtml = $this->_comment;

        # if ( strlen($this->_comment) > 0 )
        #     $xhtml = ' ' . $this->_comment . ' ';

        return sprintf('<!--%s-->'
          , $xhtml
        );
    }
}