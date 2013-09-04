<?php

class OAuth
{
    const VERSION = '1.0';

    const SIG_METHOD_HMACSHA1  = 'HMAC-SHA1';
    const SIG_METHOD_PLAINTEXT = 'PLAINTEXT';

    protected $signatureMethod;

    protected $consumerKey;
    protected $consumerSecret;

    protected $tokenKey;
    protected $tokenSecret;

    protected $callback;
    protected $response;

    public function __construct( $consumerKey, $consumerSecret, $signatureMethod = OAuth::SIG_METHOD_HMACSHA1 )
    {
        $this->consumerKey = $consumerKey;
        $this->consumerSecret = $consumerSecret;

        $this->signatureMethod = $signatureMethod;
    }

    public function setToken( $tokenKey, $tokenSecret )
    {
        $this->tokenKey = $tokenKey;
        $this->tokenSecret = $tokenSecret;
    }

    public function setCallback( $callback )
    {
        $this->callback = $callback;
    }

    public function getRequestToken( $url )
    {
        $response = $this->request(
            $this->getUrl($url)
        );

        return OAuth::paramsParse($response);
    }

    public function getAccessToken( $url )
    {
        $response = $this->request(
            $this->getUrl($url)
        );

        return OAuth::paramsParse($response);
    }

    public function fetch( $url, $post = false )
    {
        $url = $this->getUrl($url, $post);

        if ( is_array($url) )
        {
            $post = array_pop($url);
            $url  = array_pop($url);
        }

        $response = $this->request(
            $url, $post
        );

        return $response;
    }

    public function fetch2( $url, $post = false )
    {
        try {
            $response = $this->fetch($url, $post);
        }
        catch ( Exception $e ) {
            $response = '';
        }

        return $response;
    }

    public function getLastResponse()
    {
        return $this->response;
    }

    protected function request( $url, $post = false )
    {
        if ( true === $post || is_array($post) )
            $curl = new Curl($url, CURLOPT_POST, ( true === $post ) ? array() : $post);
        else
            $curl = new Curl($url);

        $curl->request();
        $this->response = $curl->response();
        $curl->disconnect();

        if ( $this->response['status'] )
        {
            if ( $this->response['status']['code'] == 200 )
                return $this->response['body'];

            throw new Exception($this->response['status']['message'], $this->response['status']['code']);
        }

        throw new Exception('Error!');
    }

    protected function getUrl( $url, $post = false )
    {
        $defaults = array(
            'oauth_version'      => OAuth::VERSION,
            'oauth_nonce'        => OAuth::getNonce(),
            'oauth_timestamp'    => OAuth::getTimestamp(),
            'oauth_consumer_key' => $this->consumerKey,
            'oauth_callback'     => $this->callback ? $this->callback : ''
        );

        if ( $this->tokenKey )
            $defaults['oauth_token'] = $this->tokenKey;

        $params = array();

        $urlnormalize = OAuth::urlnormalize($url);
        $url = $urlnormalize['url'];
        $urlquery = $urlnormalize['query'];

        if ( !( empty($urlquery) ) )
            parse_str($urlquery, $params);

        $params = array_merge($defaults, $params);

        if ( is_array($post) )
            $params = array_merge($params, $post);

        $params['oauth_signature_method'] = $this->signatureMethod;
        $params['oauth_signature'] = $this->getSignature($post ? 'POST' : 'GET', $url, $params);

        if ( $post )
        {
            $params['oauth_signature'] = rawurldecode(
                $params['oauth_signature']
            );

            return array( $url, $params );
        }

        return $url . '?' . OAuth::paramsBuild( $params );
    }

    protected function getSignature( $method, $url, $params )
    {
        $signature = false;

        /**
         * Normalize method.
         */
        $method = strtoupper($method);

        /**
         * Normalize URL.
         *
         * $urlnormalize = OAuth::urlnormalize($url);
         * $url = $urlnormalize['url'];
         */

        /**
         * Remove `oauth_signature` if present.
         * Ref: Spec: 9.1.1 ("The oauth_signature parameter MUST be excluded.")
         */
        if ( isset($params['oauth_signature']) )
            unset($params['oauth_signature']);

        /**
         * Generate key.
         */
        $keyParts = array( $this->consumerSecret, ( $this->tokenSecret ) ? $this->tokenSecret : '' );
        $key = implode( '&', OAuth::urlencode($keyParts) );

        switch ( $this->signatureMethod )
        {
            case OAuth::SIG_METHOD_HMACSHA1:
                $baseParts = array( $method, $url, OAuth::paramsBuild($params) );
                $base = implode( '&', OAuth::urlencode($baseParts) );

                $signature = base64_encode( hash_hmac('sha1', $base, $key, true) );
                break;

            case OAuth::SIG_METHOD_PLAINTEXT:
                $signature = $key;
                break;
        }

        if ( false === $signature )
            throw new Exception('Can\'t generate signature.');

        return $signature;
    }

    protected static function getNonce()
    {
        $mt = microtime();
        $rand = mt_rand();

        return md5($mt . $rand);
    }

    protected static function getTimestamp()
    {
        return time();
    }

    protected static function urlencode( $input )
    {
        if ( is_array($input) )
        {
            return array_map(array('OAuth', 'urlencode'), $input);
        }
        elseif ( is_scalar($input) )
        {
            return str_replace( '+', ' ',
                str_replace( '%7E', '~', rawurlencode($input) )
            );
        }

        return '';
    }

    /**
     * This decode function isn't taking into consideration the above
     * modifications to the encoding process. However, this method doesn't
     * seem to be used anywhere so leaving it as is.
     *
     * @param string $string
     * @return string
     */
    protected static function urldecode( $string )
    {
        return urldecode($string);
    }

    protected static function urlnormalize( $url )
    {
        $parts = parse_url($url);

        $port   = ( isset($parts['port']) ) ? $parts['port'] : null;
        $scheme = $parts['scheme'];
        $host   = $parts['host'];
        $path   = ( isset($parts['path']) ) ? $parts['path'] : '';
        $query  = ( isset($parts['query']) ) ? $parts['query'] : '';

        if ( is_null($port) )
            $port = ( $scheme == 'https' ) ? 443 : 80;

        if ( ( $scheme == 'https' && $port != 443 ) || ( $scheme == 'http' && $port != 80 ) )
            $host = "$host:$port";

        return array(
            'url'   => "$scheme://$host$path",
            'query' => $query
        );
    }

    protected static function paramsParse( $input )
    {
        if ( !( isset($input) ) || !( $input ) )
            return array();

        $pairs = explode('&', $input);
        $parsedParams = array();

        foreach ( $pairs as $pair )
        {
            $split = explode('=', $pair, 2);
            $parameter = trim( self::urldecode($split[0]) );
            $value = isset($split[1]) ? trim( self::urldecode($split[1]) ) : '';

            if ( !( isset($parsedParams[$parameter]) ) )
                $parsedParams[$parameter] = $value;
            else
            {
                /**
                 * We have already recieved parameter(s) with this name, so add
                 * to the list of parameters with this name.
                 */
                if ( is_scalar($parsedParams[$parameter]) )
                {
                    /**
                     * This is the first duplicate, so transform scalar (string)
                     * into an array so we can add the duplicates.
                     */
                    $parsedParams[$parameter] = array($parsedParams[$parameter]);
                }

                $parsedParams[$parameter][] = $value;
            }
        }

        return $parsedParams;
    }

    protected static function paramsBuild( $params )
    {
        if ( !( $params ) )
            return '';

        $keys   = self::urlencode( array_keys($params) );
        $values = self::urlencode( array_values($params) );
        $params = array_combine($keys, $values);

        /**
         * Parameters are sorted by name, using lexicographical byte value
         * ordering. Ref: Spec: 9.1.1 (1)
         */
        uksort($params, 'strcmp');

        $pairs = array();

        foreach ( $params as $parameter => $value )
        {
            if ( !( is_array($value) ) )
                $pairs[] = $parameter . '=' . $value;
            else
            {
                /**
                 * If two or more parameters share the same name, they are
                 * sorted by their value. Ref: Spec: 9.1.1 (1)
                 */
                natsort($value);

                foreach ( $value as $value2 )
                    $pairs[] = $parameter . '=' . $value2;
            }
        }

        /**
         * For each parameter, the name is separated from the corresponding
         * value by an '=' character (ASCII code 61). Each name-value pair is
         * separated by an '&' character (ASCII code 38).
         */
        return implode('&', $pairs);
    }
}