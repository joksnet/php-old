<?php

class Css
{
    public $filename = '';
    public $type = 'text/css';

    protected $css = array();
    protected $options = array(
        'compress' => true,
        'gzcompress' => false
    );

    public function __construct( $argv = array() )
    {
        $filename = Path::join( array('css', $argv[0]) );

        if ( substr($filename, -4) != '.css' )
            $filename .= '.css';

        if ( is_readable($filename) )
            $this->filename = $filename;

        //-->

        if ( isset($_GET['compress']) )
            $this->options['compress'] = $_GET['compress'];

        //-->

        if ( empty($this->filename) )
            $this->parseAll();
        elseif ( is_readable($this->filename) )
            $this->css[$this->filename] = $this->parse( file_get_contents($this->filename) );
    }

    protected function parseAll()
    {
        $d = dir( Path::real('css') );

        while ( false !== ( $file = $d->read() ) )
        {
            if ( strncmp($file, '.', 1) != 0 )
            {
                $filename = Path::rawjoin( array($d->path, $file) );

                if ( is_readable($filename) )
                    $this->css[$filename] = $this->parse( file_get_contents($filename) );
            }
        }

        $d->close();
    }

    protected function parse( $css )
    {
        $varsKeys = array();
        $varsValues = array();

        $lines = explode("\n", $css);
        $lines2 = array();

        foreach ( $lines as $line )
        {
            # if ( strncmp( strtolower(ltrim($line)), 'var', 3 ) === 0 )
            if ( @preg_match('~def __([a-zA-Z0-9_]+)__ = (.+)~', $line, $params) )
            {
                array_push($varsKeys, '__' . $params[1] . '__');
                array_push($varsValues, $params[2]);
            }
            elseif ( strncmp( strtolower(ltrim($line)), 'def', 3 ) === 0 ) {}
            else
            {
                array_push($lines2, str_replace( $varsKeys, $varsValues, $line ) );
            }
        }

        $return = "\n" . implode("\n", $lines2);

        if ( $this->options['compress'] )
            $return = $this->compress( $return );

        if ( $this->options['gzcompress'] )
            $return = gzcompress($return);

        return $return;
    }

    /**
     * Very simple CSS optimizer
     *
     * @author Andreas Gohr <andi@splitbrain.org>
     * @author Juan M Martinez <joksnet@gmail.com>
     */
    protected function compress( $css )
    {
        // strip comments through a callback
        $css = preg_replace_callback('#(/\*)(.*?)(\*/)#s', create_function('$matches', 'if ( strlen($matches[2]) > 4 ) return ""; return $matches[0];'), $css);

        // strip (incorrect but common) one line comments
        $css = preg_replace('/(?<!:)\/\/.*$/m', '', $css);

        // strip whitespaces
        $css = preg_replace('![\r\n\t ]+!', ' ', $css);
        $css = preg_replace('/ ?([:;,{}\/]) ?/', '\\1', $css);

        // shorten colors
        $css = preg_replace("/#([0-9a-fA-F]{1})\\1([0-9a-fA-F]{1})\\2([0-9a-fA-F]{1})\\3/", "#\\1\\2\\3", $css);

        return ltrim($css);
    }

    public function __toString()
    {
        if ( sizeof($this->css) > 0 )
            return implode('', $this->css);
        else
            return '';
    }
}