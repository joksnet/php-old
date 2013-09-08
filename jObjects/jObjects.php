<?php

class jObject
{
    const DATA_DIRECTORY = 'jObjects/';
    const FILE_EXTENSION = 'txt';

    protected $_instance = null;
    protected $_id = 0;
    protected $_name = '';
    protected $_args = null;

    public function __construct( $id = null, $name = null, $args = null )
    {
        settype($id, 'int');
        settype($name, 'string');
        settype($args, 'array');

        if ( $id > 0 )
            $this->load($id);
        elseif ( strlen($name) > 0 )
        {
            if ( !( class_exists($name) ) )
                throw new Exception("Class $name not exists");

            // if ( sizeof($args) > 0 )
            // {
                $reflection = new ReflectionClass($name);

                if ( !( $reflection->hasProperty('jObjects') ) )
                    throw new Exception("Class not supported by jObjects");

                $this->_instance = call_user_func_array(array(&$reflection, 'newInstance'), $args);
            // }
            // else
            //     $this->_instance = new $name();

            $this->save();
        }
        else
            throw new Exception('Bad parameters');
    }

    public function save()
    {
        if ( $this->_instance )
        {
            if ( $this->_id == 0 )
                $this->_id = rand(0, 999);

            $fileName = $this->_id . '.' . self::FILE_EXTENSION;
            $filePath = self::DATA_DIRECTORY . $fileName;
            $fileContents = serialize($this->_instance);

            $file = fopen($filePath, 'w');

            fwrite($file, $fileContents);
            fclose($file);
        }
        else
            throw new Exception('Instance not defined');
    }

    public function load( $id = null )
    {
        settype($id, 'int');

        if ( $id > 0 )
            $this->_id = $id;
        elseif ( $this->_id > 0 )
            $id = $this->_id;
        else
            throw new Exception('Missing ID');

        $fileName = $id . '.' . self::FILE_EXTENSION;
        $filePath = self::DATA_DIRECTORY . $fileName;

        if ( !( file_exists($filePath) ) )
            throw new Exception("File $fileName not exists");

        $fileContents = file_get_contents($filePath);

        $this->_instance = unserialize($fileContents);
        $this->_name = get_class($this->_instance);
    }

    public function close()
    {
        if ( $this->_id > 0 )
        {
            $fileName = $this->_id . '.' . self::FILE_EXTENSION;
            $filePath = self::DATA_DIRECTORY . $fileName;

            unlink($filePath);
        }
    }

    public function call( $method, $args )
    {
        settype($method, 'string');
        settype($args, 'array');

        if ( !( method_exists($this->_instance, $method) ) )
            throw new Exception('Method not exists');

        if ( sizeof($args) > 0 )
            $return = call_user_func_array(array(&$this->_instance, $method), $args);
        else
            $return = $this->_instance->$method();

        $this->save();

        return $return;
    }

    public function val( $var, $value = null )
    {
        settype($var, 'string');
        settype($value, 'string');

        if ( strlen($value) == 0 )
            $return = $this->_instance->$var;
        else
        {
            $return = 1;
            $this->_instance->$var = $value;
            $this->save();
        }

        return $return;
    }

    public function id( $id = null )
    {
        if ( is_null($id) )
            return $this->_id;
        else
            $this->_id = $id;
    }
}

if ( isset($_POST['jObjects']) )
{
    header('Content-Type: text/json; charset=utf-8');

    if ( isset($_POST['id']) )
    {
        if ( isset($_POST['var']) )
        {
            try {
                $jObject = new jObject($_POST['id']);

                if ( isset($_POST['value']) )
                    $result = $jObject->val($_POST['var'], $_POST['value']);
                else
                    $result = $jObject->val($_POST['var']);

                echo "{result:'$result'}";
            } catch ( Exception $e ) {
                echo "{result:0,error:'{$e->getMessage()}',file:'{$e->getFile()}',line:{$e->getLine()}}";
            }
        }
        elseif ( isset($_POST['name']) )
        {
            try {
                $jObject = new jObject($_POST['id']);
                $result = $jObject->call($_POST['name'], $_POST['args']);

                echo "{result:'$result'}";
            } catch ( Exception $e ) {
                echo "{result:0,error:'{$e->getMessage()}',file:'{$e->getFile()}',line:{$e->getLine()}}";
            }
        }
        elseif ( isset($_POST['die']) )
        {
            try {
                $jObject = new jObject($_POST['id']);
                $jObject->close();

                echo "{result:1}";
            } catch ( Exception $e ) {
                echo "{result:0,error:'{$e->getMessage()}',file:'{$e->getFile()}',line:{$e->getLine()}}";
            }
        }
        else
        {
            echo "{result:0}";
        }
    }
    elseif ( isset($_POST['name']) )
    {
        try {
            $jObject = new jObject(null, $_POST['name'], $_POST['args']);
            $result = $jObject->id();

            echo "{result:'$result'}";
        } catch ( Exception $e ) {
            echo "{result:0,error:'{$e->getMessage()}',file:'{$e->getFile()}',line:{$e->getLine()}}";
        }
    }
    else
    {
        echo "{result:0}";
    }

    exit();
}