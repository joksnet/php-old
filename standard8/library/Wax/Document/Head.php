<?php

include_once 'Wax/Factory.php';
include_once 'Wax/Document/Element.php';
include_once 'Wax/Document/Head/Exception.php';
include_once 'Wax/Document/Head/Meta.php';
include_once 'Wax/Document/Head/Script.php';
include_once 'Wax/Document/Head/Style.php';

final class Wax_Document_Head extends Wax_Document_Element
{
    const EXT_JAVASCRIPT = '.js';
    const EXT_STYLESHEET = '.css';

    /**
     * @var Wax_Document_Element
     */
    private $_title = null;

    private $_meta = null;
    private $_contentType = 'text/html';
    private $_charset = 'utf-8';

    private $_script = null;
    private $_style = null;

    public function __construct()
    {
        parent::__construct('Head');

        $this->_title = Wax_Document::createElement('title', $this)
             ->setTagType( Wax_Document_Element::TAG_OPEN );

        $this->_meta = array();
        $this->_script = array();
        $this->_style = array();

        $this->appendMeta($this->_contentType . ';'
          . ' charset=' . $this->_charset, null, 'Content-Type');
    }

    /**
     * @return Wax_Document_Head
     */
    public function appendMeta( $content, $name = null, $httpEquiv = null )
    {
        if ( $name && strlen($name) > 0 )
            $key = $name;
        elseif ( $httpEquiv && strlen($httpEquiv) > 0 )
            $key = $httpEquiv;

        if ( strlen($key) > 0 )
        {
            if ( isset($this->_meta[$key]) )
                $this->removeChild($this->_meta[$key]);

            $this->_meta[$key] = Wax_Factory::createObject(
                'Wax_Document_Head_Meta', $content, $name, $httpEquiv
            );

            $this->appendChild($this->_meta[$key]);
        }

        return $this;
    }

    /**
     * @return Wax_Document_Head
     */
    public function importJavaScript( $fileName )
    {
        if ( $fileName instanceof Wax_Document_Node )
        {
            $classInstance = $fileName;
            $className = get_class($classInstance);
            $classReflection = new ReflectionClass($className);
            $classPath = $classReflection->getFileName();

            $fileName = '/@JavaScript/'
                      . str_replace('/', '_',
                            str_replace('.php', '',
                                str_replace('/home/vhosts/standard8.com.ar/modules/', '',
                                    str_replace(
                                        '/home/vhosts/standard8.com.ar/library/', '', $classPath
                                    )
                                )
                            )
                        );
        }
        else
        {
            if ( substr($fileName, 0, strlen('/@JavaScript/')) != '/@JavaScript/' )
                if ( substr($fileName, strlen(self::EXT_JAVASCRIPT) * -1) != self::EXT_JAVASCRIPT )
                    $fileName .= self::EXT_JAVASCRIPT;
        }

        // if ( file_exists($fileName) )
        if ( 1 )
        {
            if ( isset($this->_script[$fileName]) )
                $this->removeChild($this->_script[$fileName]);

            $this->_script[$fileName] = Wax_Factory::createObject(
                'Wax_Document_Head_Script'
            )->setAttribute('src', $fileName);

            $this->appendChild($this->_script[$fileName]);
        }

        return $this;
    }

    /**
     * @return Wax_Document_Head
     */
    public function importStyle( $fileName, $media = Wax_Document_Head_Style::MEDIA_SCREEN, $condition = null )
    {
        if ( substr($fileName, strlen(self::EXT_STYLESHEET) * -1) != self::EXT_STYLESHEET )
            $fileName .= self::EXT_STYLESHEET;

        # if ( strncmp($fileName, '../public', strlen('../public')) )
        #     $fileNameServer = '../public' . $fileName;
        # else
        #     $fileNameServer = $fileName;

        # if ( file_exists($fileNameServer) )
        if ( 1 )
        {
            $styleMedia = $media;

            if ( !( empty($condition) ) )
                $media = 'condition:' . $media;

            if ( !( isset($this->_style[$media]) ) )
            {
                $this->_style[$media] = Wax_Factory::createObject(
                    'Wax_Document_Head_Style', $styleMedia
                );

                if ( !( empty($condition) ) )
                {
                    $condObject = Wax_Factory::createObject(
                        'Wax_Document_Head_Condition', $condition, $this->_style[$media]
                    );

                    $this->appendChild($condObject);
                }
                else
                    $this->appendChild($this->_style[$media]);
            }

            $this->_style[$media]->import($fileName);
        }

        return $this;
    }

    /**
     * @return Wax_Document_Head
     */
    public function setTitle( $title, $glue = null )
    {
        if ( is_array($title) )
            $title = implode($glue, $title);

        if ( is_string($title) )
            $this->_title->innerHTML($title);

        return $this;
    }

    public function __toString()
    {
        return parent::__toString();
    }
}