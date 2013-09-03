<?php

class Url
{
    protected $path;
    protected $query;

    public function __construct( $url = null )
    {
        $query = ( is_array($url) ) ? $url : array();

        if ( is_array($url) || is_null($url) )
            $url = Request::getInstance()->getUri();

        $this->path = parse_url($url, PHP_URL_PATH);
        $this->query = parse_url($url, PHP_URL_QUERY);

        parse_str(
            $this->query,
            $this->query
        );

        $this->query = array_merge($this->query, $query);
    }

    public function getPath()
    {
        return $this->path;
    }

    public function addQuery( $name, $value )
    {
        $this->query[$name] = $value;
    }

    public function getQuery()
    {
        return http_build_query($this->query);
    }

    public function __toString()
    {
        return $this->getPath() . ( $this->query ? '?' . $this->getQuery() : '' );
    }

    public static function redirect( $url )
    {
        if ( !( $url instanceof Url ) )
            $url = new Url($url);

        Response::getInstance()->setRedirect(
            $url->__toString()
        );
    }

    public static function encode( $url )
    {
        if ( $url instanceof Url )
            $url = $url->__toString();

        $parts = explode(' ', $url);
        $encoded = array();

        foreach ( $parts as $part )
            $encoded[] = urlencode($part);

        return implode('+', $encoded);
    }
}