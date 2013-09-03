<?php
/**
 * Aix Framework
 * Copyright (c) 2009, Juan M Mart('i)nez
 */

/**
 * @see Aix_Uri_Exception
 */
require_once 'Aix/Uri/Exception.php';

class Uri
{
    protected $username = null;
    protected $password = null;

    protected $host = null;
    protected $port = null;

    protected $path     = null;
    protected $query    = null;
    protected $fragment = null;

    public function __construct( $uri )
    {
        $pattern = '~^((//)([^/?#]*))([^?#]*)(\?([^#]*))?(#(.*))?$~';
        $status  = @preg_match($pattern, $uri, $matches);

        if ( false === $status )
            throw new Aix_Uri_Exception('URI decomposition failed.');

        $this->path     = isset($matches[4]) ? $matches[4] : null;
        $this->query    = isset($matches[6]) ? $matches[6] : null;
        $this->fragment = isset($matches[8]) ? $matches[8] : null;

        $combo   = isset($matches[3]) ? $matches[3] : '';
        $pattern = '~^(([^:@]*)(:([^@]*))?@)?([^:]+)(:(.*))?$~';
        $status  = @preg_match($pattern, $combo, $matches);

        if ( false === $status )
            throw new Aix_Uri_Exception('URI auth decomposition failed.');

        $this->host = isset($matches[5]) ? $matches[5] : null;
        $this->port = isset($matches[7]) ? $matches[7] : null;

        $this->username = isset($matches[2]) ? $matches[2] : null;
        $this->password = isset($matches[4]) ? $matches[4] : null;
    }

    public function __toString()
    {
        return '';
    }

    public function getHost() { return $this->host; }
    public function setHost( $host ) { $this->host = $host; }

    public function getPort() { return $this->port; }
    public function setPort( $port ) { $this->port = $port; }

    public function getUsername() { return $this->username; }
    public function setUsername( $username ) { $this->username = $username; }

    public function getPassword() { return $this->password; }
    public function setPassword( $password ) { $this->password = $password; }

    public function getPath() { return $this->path; }
    public function setPath( $path ) { $this->path = $path; }

    public function getQuery() { return $this->query; }
    public function setQuery( $query ) { $this->query = $query; }

    public function getFragment() { return $this->fragment; }
    public function setFragment( $fragment ) { $this->fragment = $fragment; }
}