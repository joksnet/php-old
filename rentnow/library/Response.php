<?php

class Response
{
    protected static $body = array();
    protected static $headers = array();
    protected static $httpResponseCode = 200;

    public static function setHeader( $name, $value, $replace = false )
    {
        self::canSendHeaders(true);

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

    public static function setHttpResponseCode( $code )
    {
        if ( !( is_int($code) ) || ( 100 > $code ) || ( 599 < $code ) )
            throw new Exception('Invalid HTTP Response code');

        self::$httpResponseCode = $code;
    }

    public static function getHttpResponseCode()
    {
        return self::$httpResponseCode;
    }

    public static function setRedirect( $url = '', $code = 302 )
    {
        self::canSendHeaders(true);
        self::setHeader('Location', $url, true);
        self::setHttpResponseCode($code);
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
        if ( sizeof(self::$headers) > 0 )
            self::canSendHeaders(true);

        $httpCodeSent = false;

        foreach ( self::$headers as $header )
        {
            if ( $httpCodeSent || !( self::$httpResponseCode ) )
                header($header['name'] . ': ' . $header['value'], false);
            else
            {
                header(
                    $header['name'] . ': ' . $header['value'], false, self::$httpResponseCode
                );

                $httpCodeSent = true;
            }
        }

        if ( !( $httpCodeSent ) )
            header('HTTP/1.1 ' . self::$httpResponseCode);
    }

    public static function setBody( $name, $content )
    {
        self::$body[$name] = (string) $content;
    }

    public static function sendBody()
    {
        foreach ( self::$body as $content )
            echo $content;
    }

    public static function dispatch()
    {
        self::sendHeaders();
        self::sendBody();
    }
}