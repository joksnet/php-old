<?php

class Response
{
    protected static $headers = array();
    protected static $body = array();

    protected static $httpResponseCode = 200;

    public static function setHeader( $name, $value, $replace = false )
    {
        self::canSendHeaders(true);

        settype($name, 'string');
        settype($value, 'string');

        if ( $replace )
            foreach ( self::$headers as $key => $header )
                if ( $name == $header['name'] )
                    unset(self::$headers[$key]);

        self::$headers[] = array(
            'name'  => $name,
            'value' => $value
        );
    }

    public static function getHeaders()
    {
        return self::$headers;
    }

    public static function setRedirect( $url = '', $code = 302 )
    {
        self::canSendHeaders(true);
        self::setHeader('Location', $url, true);
        self::setHttpResponseCode($code);
    }

    public static function setHttpResponseCode( $code )
    {
        if ( !( is_int($code) ) || ( 100 > $code ) || ( 599 < $code ) )
            throw new Exception('Invalid HTTP Response code');
        else
            self::$httpResponseCode = $code;
    }

    public static function getHttpResponseCode()
    {
        return self::$httpResponseCode;
    }

    public static function canSendHeaders( $throw = false )
    {
        $ok = headers_sent($file, $line);

        if ( $ok && $throw )
        {
            throw new Exception(
                'Cannot send headers; headers already'
              . ' sent in ' . $file . ', line ' . $line
            );
        }

        return !( $ok );
    }

    public static function sendHeaders()
    {
        if ( sizeof(self::$headers) > 0 || ( 200 != self::$httpResponseCode) )
            self::canSendHeaders(true);
        elseif ( 200 == self::$httpResponseCode )
            return false;

        $httpCodeSent = false;

        foreach ( self::$headers as $header )
        {
            if ( !( $httpCodeSent ) && self::$httpResponseCode )
            {
                header($header['name'] . ': ' . $header['value'], false, self::$httpResponseCode);
                $httpCodeSent = true;
            }
            else
                header($header['name'] . ': ' . $header['value'], false);
        }

        if ( !( $httpCodeSent ) )
        {
            header('HTTP/1.1 ' . self::$httpResponseCode);
            $httpCodeSent = true;
        }

        return $httpCodeSent;
    }

    public static function setBody( $content, $name = null )
    {
        if ( ( null === $name ) || !( is_string($name) ) )
            self::$body = array('default' => (string) $content);
        else
            self::$body[$name] = (string) $content;
    }

    public static function appendBody( $content, $name = null )
    {
        if ( ( null === $name ) || !( is_string($name) ) )
        {
            if ( isset(self::$body['default']) )
                self::$body['default'] .= (string) $content;
            else
                return self::append('default', $content);
        }
        elseif ( isset(self::$body[$name]) )
            self::$body[$name] .= (string) $content;
        else
            return self::append($name, $content);
    }

    public static function clearBody( $name = null )
    {
        if ( null !== $name )
        {
            settype($name, 'string');

            if ( isset(self::$body[$name]) )
            {
                unset(self::$body[$name]);
                return true;
            }

            return false;
        }

        self::$body = array();
        return true;
    }

    public static function getBody( $name = null )
    {
        if ( is_null($name) )
            return self::$body;
        elseif ( is_string($name) && isset(self::$body[$name]) )
            return self::$body[$spec];

        return null;
    }

    public static function append( $name, $content )
    {
        if ( !( is_string($name) ) )
        {
            throw new Exception(
                'Invalid body segment key ("' . gettype($name) . '")'
            );
        }

        if ( isset(self::$body[$name]) )
            unset(self::$body[$name]);

        self::$body[$name] = (string) $content;
    }

    public static function prepend( $name, $content )
    {
        if ( !( is_string($name) ) )
        {
            throw new Exception(
                'Invalid body segment key ("' . gettype($name) . '")'
            );
        }

        if ( isset(self::$body[$name]) )
            unset(self::$body[$name]);

        $new = array($name => (string) $content);
        self::$body = $new + self::$body;
    }

    public static function sendBody()
    {
        foreach ( self::$body as $content )
            echo $content;
    }

    public static function sendResponse()
    {
        self::sendHeaders();
        self::sendBody();
    }
}