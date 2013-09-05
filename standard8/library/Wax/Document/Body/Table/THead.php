<?php

define('WAX_TABLE_COLUMN_NORMAL', 'normal');

class Wax_Document_Body_Table_THead extends Wax_Document_Body_Element
{
    const ALIGN_LEFT = 'left';
    const ALIGN_CENTER = 'center';
    const ALIGN_RIGHT = 'right';

    protected $_index = 0;
    protected $_rows = array();
    protected $_data = array();

    public function __construct()
    {
        parent::__construct('thead');

        $this->_rows[$this->_index] = Wax_Document::createElement('tr', $this)
             ->isContainer(true);

        $this->isContainer(true);
    }

    public function getData()
    {
        return $this->_data;
    }

    /**
     * @return Wax_Document_Body_Table_THead
     */
    public function appendColumn( $name, $label, $type = null, $protected = false, $size = null, $extras = null )
    {
        $nameColumn = $name . 'Column';

        if ( !( is_null($size) ) )
            if ( is_numeric($size) )
                $size .= 'px';

        $this->_data[$name] = array(
            'type' => $type,
            'protected' => $protected
        );

        $this->_rows[$this->_index]
             ->appendChild( Wax_Document::createElement('th')
                ->innerHTML( $label )
                ->setId($nameColumn)
                ->setAttribute('scope', 'col')
                ->setAttributes($extras)
                ->setStyle('width', $size)
             );

        return $this;
    }
}

