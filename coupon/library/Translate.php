<?php

class Translate
{
    protected $data = array();
    protected $params = array();

    public function __construct( $filename )
    {
        $this->filename = $filename;
        $this->load();
    }

    final protected function load()
    {
        $filename = $this->filename;
        $file = @fopen($filename, 'rb');

        if ( !( $file ) )
            throw new Exception("Error opening translation file \"$filename\".");

        if ( @filesize($filename) < 10 )
            throw new Exception("\"$filename\" is not a gettext file.");

        // get endian
        $input = $this->read($file, 1, false);

        if ( strtolower(substr(dechex($input[1]), -8)) == "950412de" )
            $bigEndian = false;
        elseif ( strtolower(substr(dechex($input[1]), -8)) == "de120495" )
            $bigEndian = true;
        else
            throw new Exception("\"$filename\" is not a gettext file.");

        // read revision
        $input = $this->read($file, 1, $bigEndian);

        // number of bytes
        $input = $this->read($file, 1, $bigEndian);
        $total = $input[1];

        // number of original strings
        $input = $this->read($file, 1, $bigEndian);
        $OOffset = $input[1];

        // number of translation strings
        $input = $this->read($file, 1, $bigEndian);
        $TOffset = $input[1];

        // fill the original table
        fseek($file, $OOffset);
        $origtemp = $this->read($file, 2 * $total, $bigEndian);
        fseek($file, $TOffset);
        $transtemp = $this->read($file, 2 * $total, $bigEndian);

        for ( $count = 0; $count < $total; ++$count )
        {
            if ( $origtemp[$count * 2 + 1] != 0 )
            {
                fseek($file, $origtemp[$count * 2 + 2]);

                $original = @fread($file, $origtemp[$count * 2 + 1]);
                $original = explode(chr(00), $original);
            }
            else $original[0] = '';

            if ( $transtemp[$count * 2 + 1] != 0 )
            {
                fseek($file, $transtemp[$count * 2 + 2]);

                $translate = fread($file, $transtemp[$count * 2 + 1]);
                $translate = explode(chr(00), $translate);

                if ( ( count($original) > 1 ) && ( count($translate) > 1) )
                {
                    $this->data[$original[0]] = $translate;

                    array_shift($original);

                    foreach ( $original as $orig )
                        $this->data[$orig] = '';
                }
                else $this->data[$original[0]] = $translate[0];
            }
        }

        if ( isset($this->data['']) )
        {
            $params = explode("\n", $this->data['']);

            foreach ( $params as $param )
            {
                if ( $param )
                {
                    list($name, $value) = explode(':', $param, 2);
                    $this->params[$name] = $value;
                }
            }

            unset($this->data['']);
        }

        @fclose($file);
    }

    final protected function read( $file, $bytes, $bigEndian )
    {
        if ( $bigEndian === false )
            return unpack('V' . $bytes, fread($file, 4 * $bytes));
        else
            return unpack('N' . $bytes, fread($file, 4 * $bytes));
    }

    public function get( $message )
    {
        if ( isset($this->data[$message]) )
            return $this->data[$message];

        return $message;
    }
}

function __( $message )
{
    # $message = Translate::getInstance()->get($message);

    # if ( !( 'UTF-8' == mb_detect_encoding($message) ) )
    #     $message = utf8_encode($message);

    $params  = array_slice(func_get_args(), 1);

    foreach ( $params as $i => $param )
    {
        if ( is_object($param) && method_exists($param, '__toString') )
            $param = $param->__toString();

        $message = str_replace("\$$i", $param, $message);
    }

    return $message;
}