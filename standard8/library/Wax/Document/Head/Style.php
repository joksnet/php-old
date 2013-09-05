<?php

include_once 'Wax/Document/Element.php';
include_once 'Wax/Document/Head/Exception.php';

final class Wax_Document_Head_Style extends Wax_Document_Element
{
    const MEDIA_SCREEN = 'screen';
    const MEDIA_TTY = 'tty';
    const MEDIA_TV = 'tv';
    const MEDIA_PROJECTION = 'projection';
    const MEDIA_HANDHELD = 'handheld';
    const MEDIA_PRINT = 'print';
    const MEDIA_BRAILLE = 'braille';
    const MEDIA_AURAL = 'aural';
    const MEDIA_ALL = 'all';

    private $_type = 'text/css';
    private $_import = null;
    private $_style = null;

    public function __construct( $media = self::MEDIA_SCREEN )
    {
        parent::__construct('Style');

        $this->setAttribute('type', $this->_type);
        $this->setAttribute('media', $media);

        $this->_import = array();
        $this->_style = array();
    }

    /**
     * @return Wax_Document_Head_Style
     */
    public function import( $fileName )
    {
        // if ( file_exists($fileName) )
        if ( 1 )
        {
            if ( !( in_array($fileName, $this->_import) ) )
                array_push($this->_import, $fileName);
        }

        return $this;
    }

    public function appendStyle( $styleName, $styles )
    {
        $this->_style[$styleName] = $styles;
    }

    final public function innerHTML( $xhtml )
    {
        throw new Wax_Document_Head_Exception(
            'Wax_Document_Head_Style does not allow innerHTML'
        );
    }

    public function __toString()
    {
        $xhtml = '';

        foreach ( (array) $this->_import as $fileName )
            $xhtml .= sprintf('@import url("%s");'
                , $fileName
            );

        foreach ( (array) $this->_style as $styleName => $styles )
            $xhtml .= sprintf('%s { %s }'
                , $styleName
                , implode(', ', $styles)
            );

        $this->_innerHTML = $xhtml;

        return parent::__toString();
    }
}