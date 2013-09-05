<?php

include_once 'Wax/Response/Exception.php';

class Wax_Response
{
    protected static $_instance = null;

    protected $_body = null;
    protected $_headers = null;
    protected $_httpResponseCode = 200;

    public function __construct()
    {
        $this->_body = array();
        $this->_headers = array();
    }

    /**
     * @return Wax_Response
     */
    public static function getInstance()
    {
        if ( is_null(self::$_instance) )
            self::$_instance = new self();

        return self::$_instance;
    }

    /**
     * @return Wax_Response
     */
    public function setHeader( $name, $value, $replace = false )
    {
        $this->canSendHeaders(true);

        $name  = (string) $name;
        $value = (string) $value;

        if ( $replace )
            foreach ( $this->_headers as $key => $header )
                if ( $name == $header['name'] )
                    unset($this->_headers[$key]);

        $this->_headers[] = array(
            'name'  => $name,
            'value' => $value
        );

        return $this;
    }

    public function getHeaders()
    {
        return $this->_headers;
    }

    /**
     * @return Wax_Response
     */
    public function setRedirect( $url = '', $code = 302 )
    {
        $this->canSendHeaders(true);
        $this->setHeader('Location', $url, true)
             ->setHttpResponseCode($code);

        return $this;
    }

    /**
     * @return Wax_Response
     */
    public function setHttpResponseCode( $code )
    {
        if ( !( is_int($code) ) || ( 100 > $code ) || ( 599 < $code ) )
            throw new Wax_Response_Exception('Invalid HTTP Response code');
        else
            $this->_httpResponseCode = $code;

        return $this;
    }

    public function getHttpResponseCode()
    {
        return $this->_httpResponseCode;
    }

    public function canSendHeaders( $throw = false )
    {
        $ok = headers_sent($file, $line);

        if ( $ok && $throw )
        {
            throw new Wax_Response_Exception(
                'Cannot send headers; headers already'
              . ' sent in ' . $file . ', line ' . $line
            );
        }

        return !( $ok );
    }

    /**
     * @return Wax_Response
     */
    public function sendHeaders()
    {
        if ( sizeof($this->_headers) > 0 || ( 200 != $this->_httpResponseCode) )
            $this->canSendHeaders(true);
        elseif ( 200 == $this->_httpResponseCode )
            return $this;

        $httpCodeSent = false;

        foreach ( $this->_headers as $header )
        {
            if ( !( $httpCodeSent ) && $this->_httpResponseCode )
            {
                header($header['name'] . ': ' . $header['value'], false, $this->_httpResponseCode);
                $httpCodeSent = true;
            }
            else
                header($header['name'] . ': ' . $header['value'], false);
        }

        if ( !( $httpCodeSent ) )
        {
            header('HTTP/1.1 ' . $this->_httpResponseCode);
            $httpCodeSent = true;
        }

        return $this;
    }

    /**
     * @return Wax_Response
     */
    public function setBody( $content, $name = null )
    {
        if ( ( null === $name ) || !( is_string($name) ) )
            $this->_body = array('default' => (string) $content);
        else
            $this->_body[$name] = (string) $content;

        return $this;
    }

    /**
     * @return Wax_Response
     */
    public function appendBody( $content, $name = null )
    {
        if ( ( null === $name ) || !( is_string($name) ) )
        {
            if ( isset($this->_body['default']) )
                $this->_body['default'] .= (string) $content;
            else
                return $this->append('default', $content);
        }
        elseif ( isset($this->_body[$name]) )
            $this->_body[$name] .= (string) $content;
        else
            return $this->append($name, $content);

        return $this;
    }

    public function clearBody( $name = null )
    {
        if ( null !== $name )
        {
            $name = (string) $name;

            if ( isset($this->_body[$name]) )
            {
                unset($this->_body[$name]);
                return true;
            }

            return false;
        }

        $this->_body = array();
        return true;
    }

    public function getBody( $name = null )
    {
        if ( is_null($name) )
            return $this->_body;
        elseif ( is_string($name) && isset($this->_body[$name]) )
            return $this->_body[$spec];

        return null;
    }

    /**
     * @return Wax_Response
     */
    public function append( $name, $content )
    {
        if ( !( is_string($name) ) )
        {
            throw new Wax_Response_Exception(
                'Invalid body segment key ("' . gettype($name) . '")'
            );
        }

        if ( isset($this->_body[$name]) )
            unset($this->_body[$name]);

        $this->_body[$name] = (string) $content;

        return $this;
    }

    /**
     * @return Wax_Response
     */
    public function prepend( $name, $content )
    {
        if ( !( is_string($name) ) )
        {
            throw new Wax_Response_Exception(
                'Invalid body segment key ("' . gettype($name) . '")'
            );
        }

        if ( isset($this->_body[$name]) )
            unset($this->_body[$name]);

        $new = array($name => (string) $content);
        $this->_body = $new + $this->_body;

        return $this;
    }

    /**
     * @return Wax_Response
     */
    public function sendBody()
    {
        foreach ( $this->_body as $content )
            echo $content;

        return $this;
    }

    /**
     * @return Wax_Response
     */
    public function sendResponse()
    {
        $this->sendHeaders();
        $this->sendBody();

        return $this;
    }
}