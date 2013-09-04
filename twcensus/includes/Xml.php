<?php

class Xml
{
    public static function read( $xml )
    {
        $instance = new Xml($xml);
        $elements = $instance->toArray();

        return $elements;
    }

    /**
     * @var XMLReader
     */
    protected $xml;

    /**
     * @var array
     */
    protected $elements = array();

    public function __construct( $xml )
    {
        $this->xml = new XMLReader();

        if ( preg_match( '/^<\?xml/', trim($xml) ) )
            $this->xml->XML( trim($xml) );
        else
            $this->xml->open($xml);

        $this->parse();
    }

    public function __destruct()
    {
        $this->xml->close();
    }

    protected function parse()
    {
        $depth = 0;

        $elements = array();
        $elements_[$depth] = &$elements;

        while ( $this->xml->read() )
        {
            switch ( $this->xml->nodeType )
            {
                case XMLReader::END_ELEMENT:
                    if ( ( $this->xml->depth - 1 ) < $depth )
                    {
                        $elements = &$elements_[$depth];
                        $element = &$elements[(sizeof($elements) - 1)];
                        $depth = $this->xml->depth - 1;
                    }
                    break;

                case XMLReader::ATTRIBUTE:
                    # Read does not go through attributes :(
                    break;

                case XMLReader::ELEMENT:
                    if ( strlen($this->xml->name) == 0 )
                        throw new Exception('XML node name is null');

                    if ( $this->xml->depth > $depth )
                    {
                        $depth = $this->xml->depth;
                        $elements_[$depth] = &$elements;
                        $elements = &$element['elements'];
                    }

                    $elements[] = array(
                        'tag' => $this->xml->name
                    );

                    # Working Element
                    $element = &$elements[(sizeof($elements) - 1)];

                    # Attributes
                    if ( $this->xml->hasAttributes )
                    {
                        $this->xml->moveToFirstAttribute();

                        $element['attributes'] = array();
                        $element['attributes'][$this->xml->name] = $this->xml->value;

                        while ( $this->xml->moveToNextAttribute() )
                            $element['attributes'][$this->xml->name] = $this->xml->value;
                    }

                    if ( $this->xml->isEmptyElement )
                    {
                        if ( ( $this->xml->depth - 1) < $depth )
                        {
                            $elements = &$elements_[$depth];
                            $element = &$elements[(sizeof($elements) - 1)];
                            $depth = $this->xml->depth - 1;
                        }
                    }

                    break;

                case XMLReader::TEXT:
                case XMLReader::CDATA:
                    if ( !( isset($element['value']) ) )
                        $element['value'] = $this->xml->value;
                    else
                        $element['value'] .= $this->xml->value;
                    break;
            }
        }

        $this->elements = $elements;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->elements;
    }
}