<?php

include_once 'Wax/Factory.php';
include_once 'Wax/Document/Body/Table/THead.php';
include_once 'Wax/Document/Body/Table/TFoot.php';

class Wax_Document_Body_Table extends Wax_Document_Body_Element
{
    const TYPE_NORMAL = 0;

    protected $_name = null;
    protected $_type = null;

    /**
     * @var Wax_Document_Body_Table_THead
     */
    public $tHead = null;

    /**
     * @var Wax_Document_Body_Table_TFoot
     */
    public $tFoot = null;

    public function __construct( $name = null, $type = self::TYPE_NORMAL, $extras = null )
    {
        parent::__construct('table');

        if ( !( isset($extras['cellpadding']) ) )
            $extras['cellpadding'] = 0;

        if ( !( isset($extras['cellspacing']) ) )
            $extras['cellspacing'] = 0;

        $this->_name = $name;
        $this->_type = $type || self::TYPE_NORMAL;

        $this->setId($this->_name);
        $this->setAttributes($extras);

        $this->tHead = Wax_Factory::createObject('Wax_Document_Body_Table_THead');
        $this->tFoot = Wax_Factory::createObject('Wax_Document_Body_Table_TFoot');

        $this->appendChild($this->tHead)
             ->appendChild($this->tFoot);
    }
}