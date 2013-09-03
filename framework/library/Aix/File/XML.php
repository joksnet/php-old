<?php
/**
 * Aix Framework
 * Copyright (c) 2009, Juan M Mart('i)nez
 */

/**
 * @see Aix_File_XML_Reader
 */
require_once 'Aix/File/XML/Reader.php';

/**
 * @see Aix_File_XML_Writer
 */
require_once 'Aix/File/XML/Writer.php';

/**
 * @package Aix_File_XML
 */
class Aix_File_XML
{
    /**
     * Devuelve un array a partir de un archivo o una cadena de caracteres XML.
     *
     * @param string $xml
     * @return array
     */
    public static function read( $xml )
    {
        $reader = new Aix_File_XML_Reader($xml);
        $elements = $reader->toArray();

        return $elements;
    }

    /**
     * Crea un XML a partir de un array.
     *
     * @param array $xml
     * @return string
     */
    public static function write( array $xml )
    {
        trigger_error(
            __CLASS__ . '::' . __METHOD__ . ' is not yet implemented'
        );
    }
}