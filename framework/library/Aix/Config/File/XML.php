<?php
/**
 * Aix Framework
 * Copyright (c) 2009, Juan M Mart('i)nez
 */

/**
 * @see Aix_File_XML
 */
require_once 'Aix/File/XML.php';

/**
 * @see Aix_Config
 */
require_once 'Aix/Config.php';

/**
 * @package Aix_Config
 */
class Aix_Config_File_XML extends Aix_Config
{
    /**
     * @var string
     */
    protected static $tag = 'config';

    /**
     * @param string $xml
     * @param string|null $section
     */
    public function __construct( $xml, $section = null )
    {
        $config = array();
        $elements = Aix_File_XML::read($xml);

        if ( $elements[0]['tag'] == self::$tag )
            $elements = $elements[0]['elements'];

        foreach ( $elements as $element )
        {
            $value = $this->parse($element);

            if ( isset($element['attributes']['extends']) && isset($config[$element['attributes']['extends']]) )
                $value = $this->merge( $config[$element['attributes']['extends']], $value );

            $config[$element['tag']] = $value;
        }

        if ( !( null === $section ) && isset($config[$section]) )
            $config = $config[$section];

        parent::__construct($config);
    }

    protected function parse( $node )
    {
        if ( isset($node['attributes']['value']) )
            return $node['attributes']['value'];
        elseif ( isset($node['value']) )
            return $node['value'];

        $elements = ( isset($node['elements']) ) ? $node['elements'] : $node;

        foreach ( $elements as $element )
        {
            if ( !( is_array($element) ) )
                return null;

            $return[$element['tag']] = $this->parse($element);
        }

        return $return;
    }

    protected function merge( $a, $b )
    {
        foreach ( $b as $k => $v )
        {
            if ( !( is_array($v) ) )
                $a[$k] = $v;
            else
            {
                if ( !( isset($a[$k]) ) )
                    $a[$k] = $v;
                else
                    $a[$k] = $this->merge($a[$k], $v);
            }
        }

        return $a;
    }
}