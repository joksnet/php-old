<?php

include_once 'Wax/Document/Body/Form/Element.php';

class Wax_Document_Body_Form_Element_SpinButton extends Wax_Document_Body_Form_Element
{
    protected $_step = 1;
    protected $_low = null;
    protected $_high = null;

    public function __construct( $name, $value = null, $range = array() )
    {
        Wax_Document::$head->importJavaScript($this);

        $step = 1;
        $low = null;
        $high = null;
        $maxlength = null;

        if ( sizeof($range) > 0 )
        {
            if ( sizeof($range) == 3 )
                list($low, $high, $step) = array_values($range);
            elseif ( sizeof($range) == 2 ) {
                list($low, $high) = array_values($range); $step = 1; }
            elseif ( sizeof($range) == 1 ) {
                list($high) = array_values($range); $low = 0; $step = 1; }

            if ( strlen( strval($high) ) > strlen( @strval($low) ) )
                $maxlength = strlen( strval($high) );
            else
                $maxlength = strlen( strval($low) );
        }

        if ( is_null($value) )
        {
            if ( $low && $high )
            {
                if ( $low <= 0 && $high >= 0 )
                    $value = 0;
                else
                    $value = $low;
            }
        }

        $this->_low = $low;
        $this->_high = $high;
        $this->_step = $step;

        parent::__construct('Input', $name);

        $this->addClass('spin')
             ->setAttribute('value', $value)
             ->setAttribute('maxlength', $maxlength)
             ->setAttribute('size', $maxlength);
    }

    public function getRange() { return array($this->getLow(), $this->getHigh(), $this->getStep()); }
    public function getLow() { return intval( $this->_low ); }
    public function getHigh() { return intval( $this->_high ); }
    public function getStep() { return intval( $this->_step ); }
}