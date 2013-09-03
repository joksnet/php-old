<?php

class Response
{
    /**
     * @var Response
     */
    protected static $instance;

    /**
     * @return Response
     */
    public static function getInstance()
    {
        if ( !( self::$instance instanceof Response ) )
            self::$instance = new Response();

        return self::$instance;
    }

    protected $body = array();
    protected $headers = array();
    protected $httpResponseCode = 200;

    public function setHttpResponseCode( $code )
    {
        if ( !( is_int($code) ) || ( 100 > $code ) || ( 599 < $code ) )
            throw new Exception('Invalid HTTP Response code');

        $this->httpResponseCode = $code;
    }

    public function getHttpResponseCode()
    {
        return $this->httpResponseCode;
    }

    public function setRedirect( $url = '', $code = 302 )
    {
        $this->canSendHeaders(true);
        $this->setHeader('Location', $url, true);
        $this->setHttpResponseCode($code);
    }

    public function setHeader( $name, $value, $replace = false )
    {
        $this->canSendHeaders(true);

        if ( $replace )
            foreach ( $this->headers as $key => $header )
                if ( $name == $header['name'] )
                    unset($this->headers[$key]);

        $this->headers[] = array(
            'name'  => $name,
            'value' => $value
        );
    }

    public function canSendHeaders( $throw = false )
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

    public function sendHeaders()
    {
        if ( sizeof($this->headers) > 0 )
            $this->canSendHeaders(true);

        $httpCodeSent = false;

        foreach ( $this->headers as $header )
        {
            if ( $httpCodeSent || !( $this->httpResponseCode ) )
                header($header['name'] . ': ' . $header['value'], false);
            else
            {
                header(
                    $header['name'] . ': ' . $header['value'], false, $this->httpResponseCode
                );

                $httpCodeSent = true;
            }
        }

        if ( !( $httpCodeSent ) )
            header('HTTP/1.1 ' . $this->httpResponseCode);
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function setBody( $name, $content )
    {
        $this->body[$name] = (string) $content;
    }

    public function sendBody()
    {
        foreach ( $this->body as $content )
            echo $content;
    }

    public function dispatch()
    {
        $this->sendHeaders();
        $this->sendBody();
    }
}