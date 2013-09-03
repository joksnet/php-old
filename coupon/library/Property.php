<?php

abstract class Property
{
    protected $length  = null;
    protected $default = null;
    protected $extras  = null;
    protected $notnull = true;

    protected $value;

    public function __construct()
    {
        //
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name()
             . ( null === $this->length  ? '' : "($this->length)" )
             . ( null === $this->default ? '' : " DEFAULT '$this->default'" )
             . ( null === $this->extras  ? '' : " $this->extras" )
             . ( true === $this->notnull ? ' NOT NULL' : '' );
    }

    /**
     * @return string
     */
    protected abstract function name();

    /**
     * Clear property value.
     */
    public function clear()
    {
        $this->set($this->default);
    }

    /**
     * @param mixed $value
     * @return boolean
     */
    public function valid( $value )
    {
        try {
            $new = self::validate($value);
            return true;
        }
        catch ( Exception $e ) {
            return false;
        }
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function validate( $value )
    {
        return $value;
    }

    /**
     * @param mixed $value
     */
    public function set( $value )
    {
        $data = self::validate($value);
        $this->value = $data;
    }

    /**
     * @return mixed
     */
    public function get()
    {
        if ( $this->has() )
            return $this->value;
        else
            return $this->default;
    }

    /**
     * @return boolean
     */
    public function has()
    {
        return isset($this->value);
    }
}