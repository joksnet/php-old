<?php

class Icon
{
    public $name = '';
    public $type = 'image/png';

    public function __construct( $argv = array() )
    {
        $this->name = $argv[0];
    }

    public function __toString()
    {
        $filename = Path::join( array($path, 'images', 'famfamfam', $this->name) );

        if ( substr($filename, -4) != '.png' )
            $filename .= '.png';

        if ( is_readable($filename) )
            return file_get_contents($filename);
        else
            return '';
    }
}