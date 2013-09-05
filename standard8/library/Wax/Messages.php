<?php

include_once 'Wax/Factory.php';
include_once 'Wax/Session.php';
include_once 'Wax/Session/Namespace.php';

class Wax_Messages implements IteratorAggregate
{
    protected static $_instance = null;
    protected $_messages = null;

    /**
     * @var Wax_Session_Namespace
     */
    protected $_msgData;

    /**
     * @return Wax_Messages
     */
    public static function getInstance()
    {
        if ( is_null(self::$_instance) )
            self::$_instance = new self();

        return self::$_instance;
    }

    public function __construct()
    {
        $this->_messages = array();
        $this->_msgData = Wax_Factory::createObject(
            'Wax_Session_Namespace', 'Wax_Messages'
        );

        foreach ( $this->_msgData as $message )
        	$this->_messages[] = $message;
    }

    /**
     * @return Wax_Messages
     */
    public function add( $message, $code = null )
    {
        $code = ( is_null($code) ) ? 'null' . self::count() : $code;

        $this->_msgData->add($code, $message);
        $this->_messages[$code] = $message;

        return $this;
    }

    /**
     * @return Wax_Messages
     */
    public function clear()
    {
        $this->_messages = array();

        if ( $this->_msgData )
            $this->_msgData->clear();

        return $this;
    }

    public function count()
    {
        return sizeof($this->_messages);
    }

    public function getIterator()
    {
        return new ArrayObject($this->_messages);
    }
}