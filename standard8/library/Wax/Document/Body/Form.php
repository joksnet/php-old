<?php

include_once 'Wax/Factory.php';
include_once 'Wax/Document/Body/Form/Exception.php';
include_once 'Wax/Document/Body/Form/Element.php';
include_once 'Wax/Document/Body/Form/Element/Input.php';
include_once 'Wax/Document/Body/Form/RadioGroup.php';

class Wax_Document_Body_Form extends Wax_Document_Body_Element
{
    const METHOD_POST = 'post';
    const METHOD_GET = 'get';

    /**
     * @var Wax_Document_Element
     */
    protected $_hiddens = null;

    /**
     * @var Wax_Document_Element
     */
    protected $_ul = null;

    /**
     * @var Wax_Document_Element
     */
    protected $_dd = null;

    protected $_dl = array();
    protected $_index = 0;

    public function __construct( $action = '', $method = self::METHOD_POST )
    {
        parent::__construct('Form');

        $this->_hiddens = Wax_Document::createElement('div', $this)
             ->setClassName('hiddens')
             ->appendChild( Wax_Document::createComment('') );

        $this->_dl[0] = Wax_Document::createElement('dl', $this);

        if ( is_null($method) )
            $method = self::METHOD_POST;

        $this->setAttribute('action', $action);
        $this->setAttribute('method', $method);

        Wax_Document::$head->importJavaScript('/javascripts/jquery.metadata.js');
        Wax_Document::$head->importJavaScript('/javascripts/jquery.validate.js');
        Wax_Document::$head->importJavaScript('/@JavaScript/Wax_Document_Body_Form');
        # Wax_Document::$head->importJavaScript($this);
    }

    /**
     * @return Wax_Document_Body_Form_Element
     */
    public static function createElement( $name )
    {
        $args = func_get_args();
        $name = array_shift($args);

        try {
            if ( sizeof($args) > 0 )
                $child = Wax_Factory::createObject('Wax_Document_Body_Form_Element_' . $name, $args);
            else
                $child = Wax_Factory::createObject('Wax_Document_Body_Form_Element_' . $name);
        }
        catch ( Exception $e ) {
            $child = null;

            throw new Wax_Document_Body_Form_Exception(
                "Form element \"$name\" does not exists"
            );
        }

        return $child;
    }

    /**
     * @return Wax_Document_Body_Form
     */
    public function end()
    {
        $this->_index = sizeof($this->_dl);
        $this->_dl[$this->_index] = Wax_Document::createElement('dl', $this);

        return $this;
    }

    /**
     * @return Wax_Document_Body_Form
     */
    public function fieldset( $legend, $icon = null, $id = null, $extras = null )
    {
        $this->_index = sizeof($this->_dl);
        $this->_dl[$this->_index] = Wax_Document::createElement('dl');

        if ( is_string($icon) )
            $icon = Wax_Document::$body->createImage($icon, $legend);

        Wax_Document::createElement('fieldset', $this)
             ->appendChild( Wax_Document::createElement('legend')
                ->appendChild( $icon )
                ->appendChild( Wax_Document::createTextNode($legend) ) )
             ->appendChild( $this->_dl[$this->_index] )
             ->setAttributes( $extras );

        return $this;
    }

    /**
     * @return Wax_Document_Body_Form
     */
    public function section( $id = null, $extras = null )
    {
        if ( !( is_null($id) ) )
            $extras['id'] = $id;

        $this->_index = sizeof($this->_dl);
        $this->_dl[$this->_index] = Wax_Document::createElement('dl');

        Wax_Document::createElement('div', $this)
             ->appendChild( $this->_dl[$this->_index] )
             ->setAttributes( $extras );

        return $this;
    }

    /**
     * @return Wax_Document_Body_Form
     */
    public function appendMessages( $messages = null )
    {
        if ( is_null($messages) )
            $messages = Wax_Messages::getInstance();

        foreach ( $messages as $message )
            $this->appendMessage(null, $message, true);

        $messages->clear();
    }

    /**
     * @return Wax_Document_Body_Form
     */
    public function appendMessage( $name, $message, $visible = false )
    {
        $code = null;
        $event = '';

        if ( strpos($name, ':') !== false )
        {
            list($name, $event) = explode(':', $name);
            return $this;
        }

        if ( is_null($this->_ul) )
        {
            $this->_ul = Wax_Document::createElement('ul')
                 ->setTagType( Wax_Document_Element::TAG_OPEN )
                 ->isContainer(true);

            $this->insertBefore( Wax_Document::createElement('div')
                 ->setStyle('display', 'none')
                 ->setClassName('messages')
                 ->appendChild( Wax_Document::createElement('a')
                    ->setClassName('dismiss')
                    ->appendChild( Wax_Document::$body->createImage(
                        Standard8_Uri::createUriIcon('cross')
                      , __('Cerrar')
                    ) ) )
                 ->appendChild( $this->_ul )
                 ->isContainer(true)
               , $this->_dl[0]
            );
        }

        $li = Wax_Document::createElement('li');

        if ( !( $visible ) )
            $li->setStyle('display', 'none');

        if ( !( is_null($code) ) )
        {
            $li->appendChild(
                Wax_Document::createElement('span')
                    ->setClassName('code')
                    ->innerHTML( $code )
            );
        }

        $li->appendChild(
            Wax_Document::createElement('label')
                ->setAttribute('for', $name)
                ->setClassName('invalid')
                ->innerHTML( $message )
        );

        $this->_ul
             ->appendChild( $li );

        return $this;
    }

    /**
     * @return Wax_Document_Body_Form
     */
    public function appendLabel( $name, $label = null, $child = null )
    {
        if ( !( $label ) )
            $label = ucfirst($name);

        $this->_dd = Wax_Document::createElement('dd')->appendChild($child);
        $this->_dl[$this->_index]->appendChild(
            Wax_Document::createElement('dt')
                ->appendChild(
                    Wax_Document::createElement('label')
                        ->innerHTML($label)
                        ->setAttribute('for', $name)
                )
        )->appendChild($this->_dd);

        return $this;
    }

    /**
     * @return Wax_Document_Body_Form
     */
    public function appendElement( $name, $label = null, $child = null, $inline = false )
    {
        if ( $inline && $this->_dd )
            $this->_dd->appendChild(
                    Wax_Document::createTextNode('&nbsp;')
                )
                ->appendChild($child);
        else
        {
            if ( $label )
                $this->appendLabel($name, $label, $child);
            elseif ( $child )
            {
                $this->_dd = Wax_Document::createElement('dd')->appendChild($child);
                $this->_dl[$this->_index]->appendChild($this->_dd);
            }
        }

        return $this;
    }

    /**
     * @return Wax_Document_Body_Form
     */
    public function appendElementInline( $child = null )
    {
        return $this->appendElement(null, null, $child, true);
    }

    /**
     * @return Wax_Document_Body_Form
     */
    public function appendHidden( $name, $value = null, $extras = null )
    {
        if ( strlen($name) == 0 )
        {
            throw new Wax_Document_Body_Form_Exception(
                "Hidden name must be a non-empty string"
            );
        }

        $this->_hiddens->appendChild(
            Wax_Factory::createObject(
                'Wax_Document_Body_Form_Element_Input_Hidden', $name, $value
            )->setAttributes($extras)
        );

        return $this;
    }

    /**
     * @return Wax_Document_Body_Form
     */
    public function appendText( $name, $label = null, $value = null, $maxlength = null, $required = false, $inline = false, $extras = null )
    {
        #
        # if ( $required )
        #     $extras['class'] = ( isset($extras['class']) ) ? $extras['class'] . ' required' : 'required';
        #

        $child = self::createElement('Input_Text', $name, $value)
               ->setAttribute('title', $label)
               ->setAttribute('maxlength', $maxlength)
               ->setAttributes($extras);

        if ( $required )
            $child->setAttribute('alt', '{required:true}')
                  ->addClass('required');

        if ( !( $child->hasAttribute('size') || empty($maxlength) ) )
            $child->setAttribute('size', strval( intval( $maxlength - ( ( 100 * ( pow($maxlength, 0.5) ) ) / 60 ) ) ) );

        return $this->appendElement($name, $label, $child, $inline);
    }

    /**
     * @return Wax_Document_Body_Form
     */
    public function appendNumeric( $name, $label = null, $value = null, $range = array(), $required = false, $inline = false, $extras = null )
    {
        Wax_Factory::includeClass('Wax_Json');

        #
        # if ( $required )
        #     $extras['class'] = ( isset($extras['class']) ) ? $extras['class'] . ' required-number' : 'required-number';
        # else
        #     $extras['class'] = ( isset($extras['class']) ) ? $extras['class'] . ' number' : 'number';
        #

        $child = self::createElement('SpinButton', $name, $value, $range)
               ->setAttribute('title', $label)
               ->setAttributes($extras);

        $altConfig = array();
        $altConfig['rangeValue'] = array($child->getLow(), $child->getHigh());
        $altConfig['step'] = $child->getStep();

        if ( $required )
        {
            $altConfig['required'] = true;
            $child->addClass('required');
        }

        $child->setAttribute('alt', Wax_Json::encode($altConfig));

        return $this->appendElement($name, $label, $child, $inline);
    }

    /**
     * @return Wax_Document_Body_Form
     */
    public function appendPassword( $name, $label = null, $maxlength = null, $required = false, $inline = false, $extras = null )
    {
        if ( $required )
            $extras['class'] = ( isset($extras['class']) ) ? $extras['class'] . ' required' : 'required';

        $this->appendElement($name, $label
            , self::createElement('Input_Password', $name)
                ->setAttribute('title', $label)
                ->setAttribute('maxlength', $maxlength)
                ->setAttributes($extras)
            , $inline
        );

        return $this;
    }

    /**
     * @return Wax_Document_Body_Form
     */
    public function appendCheckbox( $name, $label = null, $value = null, $options = null, $required = false, $inline = false, $extras = null )
    {
        # if ( $required )
        #     $extras['class'] = ( isset($extras['class']) ) ? $extras['class'] . ' required' : 'required';

        $child = self::createElement('Input_Checkbox', $name, $value, $options)
               ->setAttribute('title', $label)
               ->setAttributes($extras);

        if ( $required )
            $child->setAttribute('alt', '{required:true}')
                  ->addClass('required');

        $checkbox = Wax_Document::createElement('span')
            ->appendChild($child)
            ->appendChild( Wax_Document::createTextNode('&nbsp;') )
            ->appendChild(
                Wax_Document::createElement('label')
                    ->innerHTML($label)
                    ->setAttribute('for', $name)
            );

        return $this->appendElement($name, null, $checkbox, $inline);
    }

    /**
     * @return Wax_Document_Body_Form
     */
    public function appendRadioGroup( $options, $name, $label = null, $selected = null, $listsep = null, $required = false, $inline = false, $extras = null )
    {
        $radioGroupId = $name . 'Group';
        $radioGroup = Wax_Factory::createObject('Wax_Document_Body_Form_RadioGroup'
          , $options, $name, $selected, $listsep
        )->setAttributes($extras);

        $radioGroup->setId($radioGroupId);

        $this->appendElement(
            $radioGroupId, $label
          , $radioGroup, $inline);

        return $this;
    }

    /**
     * @return Wax_Document_Body_Form
     */
    public function appendTextarea( $name, $label = null, $value = null, $row = null, $cols = null, $required = false, $inline = false, $extras = null )
    {
        # if ( $required )
        #     $extras['class'] = ( isset($extras['class']) ) ? $extras['class'] . ' required' : 'required';

        $child = self::createElement('Textarea', $name, $value, $row, $cols)
               // ->setAttribute('title', $label)
               ->setAttributes($extras);

        if ( $required )
            $child->addClass('required');
               // ->setAttribute('alt', '{required:true}');

        return $this->appendElement($name, $label, $child, $inline);;
    }

    /**
     * @return Wax_Document_Body_Form
     */
    public function appendButton( $name, $label = null, $inline = false, $extras = null )
    {
        $this->appendElement($name, null
            , self::createElement('Input_Button', $name, $label)
                ->setAttributes($extras)
            , $inline
        );

        return $this;
    }

    /**
     * @return Wax_Document_Body_Form
     */
    public function appendSubmit( $name, $label = null, $inline = false, $extras = null )
    {
        $this->appendElement($name, null
            , self::createElement('Input_Submit', $name, $label)
                ->setAttributes($extras)
            , $inline
        );

        return $this;
    }

    /**
     * @return Wax_Document_Body_Form
     */
    public function appendSubmitOrCancel( $name, $label = null, $cancelUri = null, $cancelExtras = null )
    {
        $this->appendSubmit($name, $label)
             ->appendElementInline( Wax_Document::createTextNode( __('OAcentuada') ) )
             ->appendElementInline( Wax_Document::createElement('a')
                ->setAttribute('href', $cancelUri)
                ->setAttributes($cancelExtras)
                ->innerHTML( __('Cancelar') )
             );

        return $this;
    }

    /**
     * @return Wax_Document_Body_Form
     */
    public function appendSubmitRow( $addAnother = true, $continue = true, $delete = true )
    {
        $div = Wax_Document::createElement('div')
             ->setClassName('submit');

        return $this->appendChild($div);
    }

    /**
     * @return Wax_Document_Body_Form
     */
    public function appendReset( $name, $label = null, $inline = false, $extras = null )
    {
        $this->appendElement($name, null
            , self::createElement('Input_Reset', $name, $label)
                ->setAttributes($extras)
            , $inline
        );

        return $this;
    }

    /**
     * @return Wax_Document_Body_Form
     */
    public function appendImage( $name, $src, $label = null, $inline = false, $extras = null )
    {
        // if ( file_exists($src) )
        if ( 1 )
        {
            $this->appendElement($name, null
                , self::createElement('Input_Image', $name, $src, $label)
                    ->setAttributes($extras)
                , $inline
            );
        }

        return $this;
    }

    /**
     * @return Wax_Document_Body_Form
     */
    public function appendSelect( $options, $name, $label = null, $selected = null, $required = false, $inline = false, $extras = null )
    {
        #
        # if ( $required )
        #    $extras['class'] = ( isset($extras['class']) ) ? $extras['class'] . ' required' : 'required';
        #
        # if ( !( $extras['title'] ) )
        #     $extras['title'] = $label;
        #

        $child = Wax_Factory::createObject('Wax_Document_Body_Form_Select', $options, $name, $selected)
               ->setAttributes($extras);

        if ( $required )
            $child->addClass('required');
               // ->setAttribute('alt', '{required:true}');

        if ( !( $extras['title'] ) )
            $child->setAttribute('title', $label);

        return $this->appendElement($name, $label, $child, $inline);
    }

    /**
     * @return Wax_Document_Body_Form
     */
    public function setHiddensClassName( $className = null )
    {
        if ( !( is_null($class) ) )
            $this->_hiddens->setClassName($className);

        return $this;
    }

    /**
     * @return Wax_Document_Body_Form
     */
    public function showMessages( $appendMessages = false )
    {
        if ( $appendMessages )
            $this->appendMessages();

        $divMessages = $this->getElementsByClassName('messages');

        if ( sizeof($divMessages) > 0 )
        {
            $divMessages = array_pop($divMessages);
            $divMessages->setStyle('display', 'block');
        }

        return $this;
    }
}
