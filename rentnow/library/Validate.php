<?php

class Validate
{
    protected $rules = array();
    protected $error = array();
    protected $valid = true;

    public function errors()
    {
        return $this->error;
    }

    public function valid()
    {
        return $this->valid;
    }

    public function add( $name, $rule = null, $value = true )
    {
        if ( is_array($name) )
        {
            foreach ( $name as $key => $value )
                $this->add($key, $value);

            return;
        }

        if ( is_array($rule) )
        {
            foreach ( $rule as $k => $v )
            {
                if ( is_string($k) )
                    $this->add($name, $k, $v);
                else
                    $this->add($name, $v);
            }

            return;
        }

        if ( preg_match('~(\w+)\[(\d*)\:(\d*)\]~', $rule, $matches) )
        {
            $this->add($name, $matches[1], $value);
            $this->add($name, 'min', $matches[2]);
            $this->add($name, 'max', $matches[3]);

            return;
        }

        if ( strpos($rule, ':') !== false )
        {
            list($r, $v) = explode(':', $rule, 2);

            $this->add($name, $r, $v);

            return;
        }

        if ( !( method_exists('Validate', $rule) ) )
            throw new Exception("Rule \"$rule\" doesn't exists.");

        $this->rules[$name][$rule] = $value;
    }

    public function remove( $name, $rule = null )
    {
        if ( null === $rule )
            unset($this->rules[$name]);
        else
            unset($this->rules[$name][$rule]);
    }

    public function validate()
    {
        foreach ( $this->rules as $name => $rules )
        {
            $subject = $this->subject($name);

            if ( !( is_array($subject) ) )
                $this->error[$name] = $this->validateRules($rules, $subject);
            else
            {
                $this->error[$name] = array();

                foreach ( $subject as $key => $value )
                    $this->error[$name][$key] = $this->validateRules($rules, $value);
            }
        }
    }

    protected function validateRules( $rules, $subject )
    {
        $error = array();

        foreach ( $rules as $rule => $value )
        {
            if ( null !== ( $value2 = $this->subject($value) ) )
                $value = $value2;

            if ( !( call_user_func(array('Validate', $rule), $subject, $value) ) )
            {
                $error[$rule] = true; $this->valid = false;
            }
        }

        return $error;
    }

    protected function subject( $name )
    {
        return ( isset($_FILES[$name]) ) ? serialize($_FILES[$name]) : ( isset($_POST[$name]) ? $_POST[$name] : null );
    }

    //-->

    public static function required( $subject, $value = true )
    {
        return ( !( empty($subject) ) );
    }

    public static function min( $subject, $value = 0 )
    {
        return ( strlen($subject) >= intval($value) );
    }

    public static function max( $subject, $value = 999 )
    {
        return ( strlen($subject) <= intval($value) );
    }

    //-->

    public static function code( $subject, $value = true )
    {
        return ( preg_match('/^[\w\d\.]+$/u', $subject) > 0 );
    }

    public static function text( $subject, $value = true )
    {
        return ( preg_match('/^[\w\d\s]+$/u', $subject) > 0 );
    }

    public static function number( $subject, $value = true )
    {
        return ( preg_match('/^[\d]+$/u', $subject) > 0 );
    }

    public static function email( $subject, $value = true )
    {
        return ( preg_match('/^[a-z0-9._-]+@[a-z0-9-]+\.[a-z.]{2,6}$/u', $subject) > 0 );
    }

    public static function equal( $subject, $value = '' )
    {
        return ( $subject == $value );
    }

    //-->

    public static function file( $subject, $value = true )
    {
        $subject = unserialize($subject);

        if ( empty($subject['tmp_name']) )
            return false;

        if ( !( is_uploaded_file($subject['tmp_name']) ) )
            return false;

        if ( !( $subject['error'] == UPLOAD_ERR_OK ) )
            return false;

        if ( $subject['size'] <= 0 )
            return false;

        return true;
    }

    public static function fileMin( $subject, $value = 256 )
    {
        $subject = unserialize($subject);

        if ( $subject['size'] <= $value )
            return false;

        return true;
    }

    public static function fileMax( $subject, $value = 5242880 )
    {
        $subject = unserialize($subject);

        if ( $subject['size'] > $value )
            return false;

        return true;
    }

    const FILETYPE_IMAGE = 'a:4:{i:0;s:10:"image/jpeg";i:1;s:9:"image/gif";i:2;s:9:"image/png";i:3;s:11:"image/x-png";}';
    const FILETYPE_TEXT  = 'a:1:{i:0;s:10:"text/plain";}';

    public static function fileType( $subject, $value = null )
    {
        if ( null === $value )
            return true;

        $subject = unserialize($subject);
        $value   = unserialize($value);

        if ( !( in_array($subject['type'], $value) ) )
            return false;

        return true;
    }
}