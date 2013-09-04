<?php

class Url
{
    protected $path;
    protected $query;

    protected $absolute = false;

    public function __construct( $url = null, $absolute = false )
    {
        if ( is_array($url) )
        {
            $this->path = parse_url(
                Request::getRequestUri(), PHP_URL_PATH
            );

            $this->query = $url;
        }
        else
        {
            if ( is_null($url) )
                $url = Request::getRequestUri();

            $this->path = parse_url($url, PHP_URL_PATH);
            $this->query = parse_url($url, PHP_URL_QUERY);

            parse_str($this->query, $this->query);
        }

        $this->absolute = $absolute;
    }

    public function getHost()
    {
        if ( !( $this->absolute ) )
            return '';

        return 'http://' . rtrim($_SERVER['HTTP_HOST'], '/');
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getQuery()
    {
        return http_build_query($this->query);
    }

    public function __toString()
    {
        return $this->getHost() . $this->getPath() . ( $this->query ? '?' . $this->getQuery() : '' );
    }

    public static function make( $idioma = null, $uri = null )
    {
        $server = explode('.', $_SERVER['HTTP_HOST']);
        $idioma = ( null === $idioma ) ? Translate::locale() : $idioma;

        if ( sizeof($server) > 2 )
            $server = array_slice($server, -2);

        if ( !( Translate::locale() == $idioma ) )
            $uri = '';

        $url = 'http://' . $idioma . '.' . implode('.', $server)
             . ( null === $uri ? Request::getRequestUri() : $uri );

        return $url;
    }

    public static function redirect( $url )
    {
        if ( !( $url instanceof Url ) )
            $url = new Url($url);

        Response::setRedirect(
            $url->__toString()
        );
    }

    public static function redirectIdioma( $idioma )
    {
        Response::setRedirect(
            Url::make($idioma)
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