<?php

class Curl
{
    public static function get( $url, $params = array() )
    {
        $curl = new self($url, CURLOPT_HTTPGET, $params);
        $resp = $curl->request();
        $curl->disconnect();

        return $resp;
    }

    public static function post( $url, $params = array() )
    {
        $curl = new self($url, CURLOPT_POST, $params);
        $resp = $curl->request();
        $curl->disconnect();

        return $resp;
    }

    protected $curl = null;
    protected $response = '';
    protected $url = '';
    protected $method = CURLOPT_HTTPGET;
    protected $port = 80;
    protected $ssl = false;
    protected $config = array(
        'timeout'        => 30,
        'timeoutConnect' => 5,
        'redirects'      => 0,
        'httpversion'    => 1.1
    );

    public function __construct( $url, $method = CURLOPT_HTTPGET, $params = array() )
    {
        if ( !( extension_loaded('curl') ) )
            throw new Exception('cURL extension has to be loaded.');

        $this->url = $url;
        $this->method = $method;
        $this->params = $params;

        $this->connect();
    }

    public function __destruct()
    {
        $this->disconnect();
    }

    public function connect()
    {
        if ( $this->curl )
            $this->close();

        $this->curl = curl_init();

        curl_setopt($this->curl, CURLOPT_URL, $this->url);

        if ( $this->port != 80 )
            curl_setopt($this->curl, CURLOPT_PORT, intval($this->port));

        curl_setopt($this->curl, CURLOPT_TIMEOUT, $this->config['timeout']);
        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, $this->config['timeoutConnect']);
        curl_setopt($this->curl, CURLOPT_MAXREDIRS, $this->config['redirects']);

        if ( $this->ssl === true )
        {
            curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 0);
        }

        if ( !( $this->curl ) )
            throw new Exception("Unable to Connect to $this->url:$this->port");

        return $this;
    }

    public function request()
    {
        curl_setopt($this->curl, ( $this->config['httpversion'] == 1.1 ) ? CURL_HTTP_VERSION_1_1 : CURL_HTTP_VERSION_1_0, true);
        curl_setopt($this->curl, $this->method, true);
        curl_setopt($this->curl, CURLOPT_HEADER, true);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);

        if ( $this->method == CURLOPT_POST )
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->params);

        $this->response = curl_exec($this->curl);

        if ( stripos($this->response, "Transfer-Encoding: chunked\r\n") )
            $this->response = str_ireplace("Transfer-Encoding: chunked\r\n", '', $this->response);

        return $this->response;
    }

    public function disconnect()
    {
        if ( is_resource($this->curl) )
            curl_close($this->curl);

        return $this;
    }

    public function resource()
    {
        return $this->curl;
    }

    public function response()
    {
        if ( empty($this->response) )
            return false;

        $status = null;
        $headers = array();
        $body = '';

        $lines = explode("\r\n", preg_replace('/\x0D\x0A[\x09\x20]+/', ' ', $this->response));

        foreach ( $lines as $line )
        {
            if ( preg_match('/([^:]+): (.+)/m', $line, $match) )
            {
                $match[1] = preg_replace('/(?<=^|[\x09\x20\x2D])./e', 'strtoupper("\0")', strtolower( trim($match[1]) ));

                if ( isset($retVal[$match[1]]) )
                    $headers[$match[1]] = array($headers[$match[1]], $match[2]);
                else
                    $headers[$match[1]] = trim($match[2]);
            }
            else
            {
                if ( !( strstr($line, 'HTTP/1.1 ') ) )
                    $body .= "$line\r\n";
                else
                {
                    if ( is_null($status) && preg_match('/HTTP\/1\.1 ([\d]+) ([\w\s\d]+)/', $line, $match) )
                        $status = array( 'code' => $match[1], 'message' => $match[2] );
                }
            }
        }

        return array(
            'status'  => $status,
            'headers' => $headers,
            'body'    => $body
        );
    }
}