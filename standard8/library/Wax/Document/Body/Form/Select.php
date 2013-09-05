<?php

include_once 'Wax/Factory.php';
include_once 'Wax/Document/Body/Form/Element.php';
include_once 'Wax/Document/Body/Form/Select/Option.php';

class Wax_Document_Body_Form_Select extends Wax_Document_Body_Form_Element
{
    protected $_selectedValue = null;

    public function __construct( $options, $name = null, $value = null )
    {
        parent::__construct('Select', $name);

        if ( substr($name, -2) == '[]' )
            $this->setAttribute('multiple', 'multiple');

        settype($value, 'array');

        foreach ( (array) $options as $optionValue => $optionLabel )
        {
            if ( is_array($optionLabel) )
            {
                $this->appendChild(
                    self::createOptionGroup($optionLabel, $optionValue, $value)
                );
            }
            else
            {
                $selected = in_array($optionValue, $value);
                $this->appendChild(
                    self::createOption($optionValue, $optionLabel, $selected)
                );
            }
        }

        $this->addClass('select');
        $this->_selectedValue = $value;
    }

    /**
     * @return Wax_Document_Element
     */
    public function setAttribute( $name, $value )
    {
        if ( $name == 'multiple' && $value == 'multiple' )
        {
            $nameAttrib = $this->getAttribute('name');

            if ( substr($nameAttrib, -2) != '[]' )
                $this->setAttribute('name', $nameAttrib . '[]');
        }

        parent::setAttribute($name, $value);
    }

    /**
     * @return Wax_Document_Body_Form_Select_Option
     */
    public static function createOption( $value, $label, $selected = false )
    {
        return Wax_Factory::createObject(
            'Wax_Document_Body_Form_Select_Option'
          , $value
          , $label
          , $selected
        );
    }

    /**
     * @return Wax_Document_Body_Form_Select_OptionGroup
     */
    public static function createOptionGroup( $options, $label, $value = null )
    {
        $optionGroup = Wax_Factory::createObject(
            'Wax_Document_Body_Form_Select_OptionGroup', $label
        );

        settype($value, 'array');

        foreach ( $options as $optionValue => $optionLabel )
        {
            $selected = in_array($optionValue, $value);
            $optionGroup->appendChild(
                self::createOption($optionValue, $optionLabel, $selected)
            );
        }

        return $optionGroup;
    }

    /**
     * @return Wax_Document_Body_Form_Select
     */
    public function appendOption( $value, $label, $selected = null )
    {
        if ( is_null($selected) )
            $selected = in_array($value, $this->_selectedValue);

        settype($selected, 'bool');

        $this->appendChild(
            self::createOption($value, $label, $selected)
        );

        return $this;
    }
}