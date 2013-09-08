<?php

class JavaScript
{
    public $bin = false;
    public $filename = '';

    public $type = 'text/javascript';

    protected $js = array();
    protected $vars = array(
        '__ip__'       => '127.0.0.1',
        '__internal__' => '192.168.0.'
    );

    protected $options = array(
        'compress' => true,
        'gzcompress' => false
    );

    public function __construct( $argv = array() )
    {
        global $config;

        $filename = $argv[0];

        if ( substr($filename, -3) != '.js' )
            $filename .= '.js';

        foreach ( array('bin', 'src') as $dir )
        {
            $this->bin = $dir == 'bin';
            $this->filename = Path::join( array($path, 'js', $dir, $filename) );

            if ( is_readable($this->filename) )
                break;

            $this->bin = false;
            $this->filename = '';
        }

        //-->

        if ( isset($_GET['compress']) )
            $this->options['compress'] = $_GET['compress'];

        //-->

        $this->set('ip', Web::getIP());
        $this->set('config', $config);

        # foreach ( $config as $key => $value )
        #     $this->set($key, $value);

        //-->

        if ( empty($this->filename) )
            $this->parseAll();
        elseif ( is_readable($this->filename) )
            $this->js[$this->filename] = $this->parse( file_get_contents($this->filename), $this->bin );
    }

    public function set( $name, $value )
    {
        $this->vars['__' . $name . '__'] = $value;
    }

    public function get( $name )
    {
        return Json::encode( $this->vars[$name] );
    }

    protected function parseAll()
    {
        foreach ( array('bin', 'src') as $dir )
            $this->parseDir($dir);
    }

    protected function parseDir( $dir )
    {
        $d = dir( Path::join( array($path, 'js', $dir) ) );

        while ( false !== ( $file = $d->read() ) )
        {
            if ( strncmp($file, '.', 1) != 0 )
            {
                $filename = Path::rawjoin( array($d->path, $file) );

                if ( is_readable($filename) )
                    $this->js[$filename] = $this->parse( file_get_contents($filename), $dir == 'bin' );
            }
        }

        $d->close();
    }

    protected function parse( $js, $bin = false )
    {
        $lines = explode("\n", $js);
        $lines2 = array();

        foreach ( $lines as $line )
        {
            if ( @preg_match('~(__[a-zA-Z0-9]+__)~', $line, $params) )
                $line = str_replace($params[1], $this->get($params[1]), $line);

            array_push($lines2, $line);
        }

        $js = implode("\n", $lines2);

        if ( $this->options['compress'] && !( $bin ) )
            $js = $this->compress($js);

        if ( $this->options['gzcompress'] )
            $js = gzcompress($js);

        return $js;
    }

    /**
     * Strip comments and whitespaces from given JavaScript Code
     *
     * This is a port of Nick Galbreath's python tool jsstrip.py which is
     * released under BSD license. See link for original code.
     *
     * @author Nick Galbreath <nickg@modp.com>
     * @author Andreas Gohr <andi@splitbrain.org>
     * @link   http://code.google.com/p/jsstrip/
     */
    protected function compress( $s )
    {
        $s = ltrim($s);     // strip all initial whitespace
        $s .= "\n";
        $i = 0;             // char index for input string
        $j = 0;             // char forward index for input string
        $line = 0;          // line number of file (close to it anyways)
        $slen = strlen($s); // size of input string
        $lch  = '';         // last char added
        $result = '';       // we store the final result here

        // items that don't need spaces next to them
        $chars = "^&|!+\-*\/%=\?:;,{}()<>% \t\n\r'\"[]";

        while ( $i < $slen )
        {
            // skip all "boring" characters.  This is either
            // reserved word (e.g. "for", "else", "if") or a
            // variable/object/method (e.g. "foo.color")
            while ( $i < $slen && ( strpos($chars,$s[$i]) === false ) )
            {
                $result .= $s{$i};
                $i = $i + 1;
            }

            $ch = $s{$i};

            // multiline comments (keeping IE conditionals)
            if ( $ch == '/' && $s{$i+1} == '*' && $s{$i+2} != '@' )
            {
                $endC = strpos($s, '*/', $i+2);

                if ( $endC === false )
                    trigger_error('Found invalid /*..*/ comment', E_USER_ERROR);

                $i = $endC + 2;
                continue;
            }

            // singleline
            if ( $ch == '/' && $s{$i+1} == '/' )
            {
                $endC = strpos($s, "\n", $i+2);

                if ( $endC === false )
                    trigger_error('Invalid comment', E_USER_ERROR);

                $i = $endC;
                continue;
            }

            // tricky.  might be an RE
            if ( $ch == '/' )
            {
                // rewind, skip white space
                $j = 1;

                while ( $s{$i-$j} == ' ' )
                {
                    $j = $j + 1;
                }

                if ( ( $s{$i-$j} == '=' ) || ( $s{$i-$j} == '(' ) )
                {
                    // yes, this is an re
                    // now move forward and find the end of it
                    $j = 1;

                    while ( $s{$i+$j} != '/' )
                    {
                        while ( ( $s{$i+$j} != '\\' ) && ( $s{$i+$j} != '/' ) )
                        {
                            $j = $j + 1;
                        }

                        if ( $s{$i+$j} == '\\' )
                            $j = $j + 2;
                    }

                    $result .= substr($s, $i, $j+1);
                    $i = $i + $j + 1;
                    continue;
                }
            }

            // double quote strings
            if ( $ch == '"' )
            {
                $j = 1;

                while ( $s{$i+$j} != '"' && ( $i+$j < $slen ) )
                {
                    if ( $s{$i+$j} == '\\' && ( $s{$i+$j+1} == '"' || $s{$i+$j+1} == '\\' ) )
                        $j += 2;
                    else
                        $j += 1;
                }

                $result .= substr($s, $i, $j+1);
                $i = $i + $j + 1;
                continue;
            }

            // single quote strings
            if ( $ch == "'" )
            {
                $j = 1;

                while ( $s{$i+$j} != "'" && ( $i+$j < $slen ) )
                {
                    if ( $s{$i+$j} == '\\' && ( $s{$i+$j+1} == "'" || $s{$i+$j+1} == '\\' ) )
                        $j += 2;
                    else
                        $j += 1;
                }

                $result .= substr($s, $i, $j+1);
                $i = $i + $j + 1;
                continue;
            }

            // whitespaces
            if ( $ch == ' ' || $ch == "\r" || $ch == "\n" || $ch == "\t" )
            {
                // leading spaces
                if ( $i+1 < $slen && ( strpos($chars, $s[$i+1]) !== false ) )
                {
                    $i = $i + 1;
                    continue;
                }

                // trailing spaces
                //  if this ch is space AND the last char processed
                //  is special, then skip the space
                $lch = substr($result, -1);

                if ( $lch && (strpos($chars, $lch) !== false ) )
                {
                    $i = $i + 1;
                    continue;
                }

                // else after all of this convert the "whitespace" to
                // a single space.  It will get appended below
                $ch = ' ';
            }

            // other chars
            $result .= $ch;
            $i = $i + 1;
        }

        return trim($result);
    }

    public function __toString()
    {
        if ( sizeof($this->js) > 0 )
            return implode('', $this->js);
        else
            return '';
    }
}