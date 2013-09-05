<?php

include_once 'Wax/Document/Body/Element.php';
include_once 'Wax/Document/Body/Form/Exception.php';

class Wax_Document_Body_Form_Element extends Wax_Document_Body_Element
{
    protected $_name = null;
    protected $_keys = null;

    public function __construct( $tagName, $name, $keys = null )
    {
        parent::__construct($tagName);

        $this->_name = $name;
        $this->_keys = array();

        foreach ( (array) $keys as $key )
            $this->appendKey($key);

        $this->generateName();
    }

    /**
     * @return Wax_Document_Body_Form_Element
     */
    protected function generateName()
    {
        $name = $this->_name;

        foreach ( (array) $this->_keys as $key => $keyData )
        {
            $name .= sprintf('[%s]'
                , $key
            );
        }

        if ( sizeof($this->_keys) == 0 )
            $this->setId($name);
        else
            $this->removeId();

        return $this->setAttribute('name', $name);
    }

    /**
     * @return Wax_Document_Body_Form_Element
     */
    public function appendKey( $key, $keyName = null )
    {
        if ( strlen($key) == 0 )
        {
            throw new Wax_Document_Body_Form_Exception(
                'The key must be a non-empty string'
            );
        }

        $this->_keys[$key] = array(
            'name' => $keyName
        );

        return $this->generateName();
    }

    /**
     * @return Wax_Document_Body_Form_Element
     */
    public function removeKey( $key )
    {
        if ( strlen($key) == 0 )
        {
            throw new Wax_Document_Body_Form_Exception(
                'The key must be a non-empty string'
            );
        }

        if ( isset($this->_keys[$key]) )
            unset($this->_keys[$key]);

        return $this->generateName();
    }

    public function getKeyByName( $keyName )
    {
        foreach ( (array) $this->_keys as $key => $keyData )
        {
            if ( $keyData['name'] == $keyName )
                return $key;
        }

        return null;
    }
}