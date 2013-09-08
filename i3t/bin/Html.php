<?php

class Html
{
    public $filename = '';
    public $type = 'text/html';

    protected $options = array(
        'compress' => true,
        'gzcompress' => false
    );

    public function __construct( $argv = array() )
    {
        $filename = ( empty($argv[0]) ) ? 'redirect' : $argv[0];
        $filename = Path::join( array($path, 'html', $filename) );

        if ( substr($filename, -5) != '.html' )
            $filename .= '.html';

        if ( is_readable($filename) )
            $this->filename = $filename;

        //-->

        if ( isset($_GET['compress']) )
            $this->options['compress'] = $_GET['compress'];
    }

    protected function parse( $html )
    {
        if ( $this->options['compress'] )
            $html = $this->compress($html);

        if ( $this->options['gzcompress'] )
            $html = gzcompress($html);

        return $html;
    }

    protected function compress( $html )
    {
        if ( $this->options['compress'] )
        {
            /**
             * @author Tobias Goldkamp
             * @link http://ru.php.net/manual/en/function.ob-start.php#71953
             */
            $html = preg_replace(array('/\>[^\S]+/s', '/[^\S]+\</s', '/(\s)+/s'), array('>', '<', '\\1'), $html);
        }

        return $html;
    }

    public function __toString()
    {
        return $this->parse( file_get_contents($this->filename) );
    }
}