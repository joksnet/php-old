<?php

/**
 * Convierte el contenido del archivo a un array
 *
 * @param string $fileName
 * @return array
 */
function xml2array( $fileName )
{
    $xml = fileGetContents($fileName);
    $xmlParser = new xmlParser();
    $xmlParser->parse($xml);

    return $xmlParser->data;
}

/**
 * Se encarga para pasar un string XML a un array
 *
 * @author bbellwfu@gmail.com
 * @link http://ar2.php.net/manual/en/function.xml-parse.php#52567
 */
class xmlParser
{
    /**
     * El resultado del parseo del XML en array
     *
     * @var array
     */
    public $data = array();

    /**
     * XML Parser
     *
     * @var resource
     */
    protected $xmlParser;

    /**
     * Resultado del parseo
     *
     * @var int
     */
    protected $xmlData;

    /**
     * Ultimo tag utilizado
     *
     * @var string
     */
    protected $lastTag = '';

    /**
     * Ante-ultimo tag utilizado
     *
     * @var string
     */
    protected $lastTag2 = '';

    /**
     * Comienza el proceso de XML pasado como string
     *
     * @param string $xml
     */
    function parse( $xml )
    {
        $this->xmlParser = xml_parser_create();

        xml_set_object($this->xmlParser, $this);
        xml_set_element_handler($this->xmlParser, 'tagOpen', 'tagClosed');
        xml_set_character_data_handler($this->xmlParser, 'tagData');

        $this->xmlData = xml_parse($this->xmlParser, $xml);

        if ( !( $this->xmlData ) )
        {
            trigger_error(
                sprintf("XML error: %s at line %d",
                    xml_error_string( xml_get_error_code($this->xmlParser) ),
                    xml_get_current_line_number($this->xmlParser)
                )
            );
        }

        xml_parser_free($this->xmlParser);
    }

    function tagOpen( $parser, $name, $attrs )
    {
        $name = strtolower($name);

        if ( sizeof($attrs) > 0 )
            $attrs = array_combine( array_map('strtolower', array_keys($attrs)), array_values($attrs) );

        if ( !( empty($this->lastTag) ) && $this->lastTag != $name )
            $this->lastTag2 = $this->lastTag;

        $this->lastTag = $name;
        $this->data[$name] = array(
            'attrs'    => $attrs,
            'children' => array()
        );
    }

    function tagData( $parser, $tagData )
    {
        if ( trim($tagData) )
        {
            if ( isset($this->data[$this->lastTag]['data']) )
                $this->data[$this->lastTag]['data'] .= $tagData;
            else
                $this->data[$this->lastTag]['data'] = $tagData;
        }
    }

    function tagClosed( $parser, $name )
    {
        $name = strtolower($name);

        if ( $name != $this->lastTag2 )
        {
            $this->data[$this->lastTag2]['children'][$name] = $this->data[$this->lastTag];
            unset($this->data[$this->lastTag]);
        }
    }
}
