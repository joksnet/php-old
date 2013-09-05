<?php

class Wax_Request
{
    protected static $_instance = null;

    protected $_checkMagicQuotes = false;
    protected $_requestUri;

    public function __construct()
    {
        $this->_checkMagicQuotes();
    }

    /**
     * @return Wax_Request
     */
    public static function getInstance()
    {
        if ( is_null(self::$_instance) )
            self::$_instance = new self();

        return self::$_instance;
    }

    protected function _checkMagicQuotes()
    {
        if ( get_magic_quotes_gpc() && !( $this->_checkMagicQuotes ) )
        {
            $this->_fixMagicQuotes($_COOKIE);
            $this->_fixMagicQuotes($_ENV);
            $this->_fixMagicQuotes($_GET);
            $this->_fixMagicQuotes($_POST);
            $this->_fixMagicQuotes($_REQUEST);
            $this->_fixMagicQuotes($_SERVER);
        }

        $this->_checkMagicQuotes = true;
    }

    protected function _fixMagicQuotes( &$array )
    {
        foreach ( $array as $key => $value )
        {
            if ( is_array( $value ) )
                $this->_fixMagicQuotes($array[$key]);
            else
                $array[$key] = stripslashes($value);
        }

        return $array;
    }

    public function __get( $key )
    {
        switch ( true )
        {
            case isset( $this->_params[$key] ):
                return $this->_params[$key];
            case isset( $_GET[$key] ):
                return $_GET[$key];
            case isset( $_POST[$key] ):
                return $_POST[$key];
            case isset( $_COOKIE[$key] ):
                return $_COOKIE[$key];
            case ( $key == 'REQUEST_URI' ):
                return $this->getRequestUri();
            case isset( $_SERVER[$key] ):
                return $_SERVER[$key];
            case isset( $_ENV[$key] ):
                return $_ENV[$key];
            default:
                return null;
        }
    }

    public function get( $key )
    {
        return $this->__get($key);
    }

    public function __isset( $key )
    {
        switch ( true )
        {
            case isset( $this->_params[$key] ):
                return true;
            case isset( $_GET[$key] ):
                return true;
            case isset( $_POST[$key] ):
                return true;
            case isset( $_COOKIE[$key] ):
                return true;
            case isset( $_SERVER[$key] ):
                return true;
            case isset( $_ENV[$key] ):
                return true;
            default:
                return false;
        }
    }

    public function has( $key )
    {
        return $this->__isset($key);
    }

    public function getQuery( $key = null, $default = null )
    {
        if ( null === $key )
            return $_GET;

        return ( isset($_GET[$key]) ) ? $_GET[$key] : $default;
    }

    public function getPost( $key = null, $default = null )
    {
        if ( null === $key )
            return $_POST;

        return ( isset($_POST[$key]) ) ? $_POST[$key] : $default;
    }

    public function getCookie( $key = null, $default = null )
    {
        if ( null === $key )
            return $_COOKIE;

        return ( isset($_COOKIE[$key]) ) ? $_COOKIE[$key] : $default;
    }

    public function getServer( $key = null, $default = null )
    {
        if ( null === $key )
            return $_SERVER;

        return ( isset($_SERVER[$key]) ) ? $_SERVER[$key] : $default;
    }

    public function getEnv( $key = null, $default = null )
    {
        if ( null === $key )
            return $_ENV;

        return ( isset($_ENV[$key]) ) ? $_ENV[$key] : $default;
    }

    /**
     * @return Wax_Request
     */
    public function setRequestUri($requestUri = null)
    {
        if ( null === $requestUri )
        {
            if ( isset($_SERVER['HTTP_X_REWRITE_URL']) )
                $requestUri = $_SERVER['HTTP_X_REWRITE_URL'];
            elseif ( isset($_SERVER['REQUEST_URI']) )
                $requestUri = $_SERVER['REQUEST_URI'];
            elseif (isset($_SERVER['ORIG_PATH_INFO']))
            {
                $requestUri = $_SERVER['ORIG_PATH_INFO'];

                if ( !( empty($_SERVER['QUERY_STRING']) ) )
                    $requestUri .= '?' . $_SERVER['QUERY_STRING'];
            }
            else
                return $this;
        }
        elseif ( !( is_string($requestUri) ) )
            return $this;
        else
        {
            $_GET = array();

            if ( false !== ( $pos = strpos($requestUri, '?') ) )
            {
                parse_str( substr($requestUri, $pos + 1), $vars );
                $_GET = $vars;
            }
        }

        if ( strlen($requestUri) > 0 )
            $this->_requestUri = $requestUri;

        return $this;
    }

    public function getRequestUri()
    {
        if ( empty($this->_requestUri) )
            $this->setRequestUri();

        return $this->_requestUri;
    }

    public function getMethod()
    {
        return $this->getServer('REQUEST_METHOD');
    }

    public function isPost()
    {
        if ( 'POST' == $this->getMethod() )
            return true;

        return false;
    }

    public function isXmlHttpRequest()
    {
        return ( $this->getHeader('X_REQUESTED_WITH') == 'XMLHttpRequest' );
    }

    public function getHeader( $header )
    {
        if ( empty($header) )
            return false;

        $temp = 'HTTP_' . strtoupper(str_replace('-', '_', $header));

        if ( !( empty($_SERVER[$temp]) ) )
            return $_SERVER[$temp];

        if ( function_exists('apache_request_headers') )
        {
            $headers = apache_request_headers();

            if ( !( empty($headers[$header]) ) )
                return $headers[$header];
        }

        return false;
    }
}