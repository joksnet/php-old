<?php

class Model
{
    /**
     * Reserved ID name.
     */
    const IDENTIFIER = 'id';

    /**
     * @var Db
     */
    protected $db;

    protected $table;

    protected $identifier = null;
    protected $properties = array();

    /**
     * @var string
     */
    protected $hash = '';

    /**
     * ---
     */
    public function __construct( $id = null )
    {
        $this->db = Db::getInstance();
        $this->table = Model::table( get_class($this) );

        $this->init();

        if ( $id )
        {
            if ( !( $this->identifier ) )
                throw new Exception('Identifier not found.');

            $this->get($id);
        }
        else
        {
            $this->hash = Model::hash(
                $this->properties
            );
        }
    }

    /**
     * @return Model
     */
    public function clear()
    {
        foreach ( $this->properties as $name => $instance )
        {
            if ( is_subclass_of($instance, 'Property') )
                $instance->clear();
            else unset($this->properties[$name]);
        }

        $this->hash = Model::hash(
            $this->properties
        );

        return $this;
    }

    /**
     * @return Model
     */
    public function synchronize()
    {
        $tables = $this->db->tables();

        if ( false === array_search($this->table, $tables) )
        {
            $properties = array();

            foreach ( $this->properties as $name => $instance )
            {
                if ( is_subclass_of($instance, 'Property') )
                    $properties[] = "`$name` " . $instance->__toString();
            }

            $this->db->execute(
                "CREATE TABLE `$this->table` (" . implode(', ', $properties) . ")"
            );
        }

        return $this;
    }

    public function __set( $name, $value )
    {
        $force = false;

        if ( substr($name, 0, 1) == ':' )
        {
            $name = substr($name, 1);
            $force = true;
        }

        if ( array_key_exists($name, $this->properties) )
        {
            if ( !( is_subclass_of($this->properties[$name], 'Property') ) )
                $this->properties[$name] = $value;
            else
            {
                if ( !( $force ) && $this->properties[$name] instanceof PropertyIdentifier )
                    throw new Exception('Identifier can not be set.');

                $this->properties[$name]->set($value);
            }

            return;
        }
        elseif ( is_object($value) && is_subclass_of($value, 'Property') )
        {
            if ( Model::IDENTIFIER == $name )
                throw new Exception('Property name `' . Model::IDENTIFIER . '` reserved.');

            if ( $value instanceof PropertyIdentifier )
            {
                if ( !( null === $this->identifier ) )
                    throw new Exception('Identifier already created.');

                $this->identifier = $name;
            }

            $this->properties[$name] = $value; return;
        }

        throw new Exception("Property `$name` not found.");
    }

    public function __get( $name )
    {
        if ( array_key_exists($name, $this->properties) )
        {
            if ( is_subclass_of($this->properties[$name], 'Property') )
                return $this->properties[$name]->get();
            else
                return $this->properties[$name];
        }
        elseif ( Model::IDENTIFIER == $name )
        {
            if ( null === $this->identifier )
                throw new Exception('Identifier not set.');

            return $this->properties[$this->identifier]->get();
        }

        throw new Exception("Property `$name` not found.");
    }

    public function __isset( $name )
    {
        try {
            $this->__get($name);
        }
        catch ( Exception $e ) {
            return false;
        }

        return true;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call( $name, array $arguments )
    {
        if ( strpos($name, 'get') === 0 )
        {
            return $this->__get(
                Model::uncamelize( substr($name, 3) )
            );
        }
        elseif ( strpos($name, 'has') === 0 )
        {
            return $this->__isset(
                Model::uncamelize( substr($name, 3) )
            );
        }
        elseif ( strpos($name, 'set') === 0 )
        {
            if ( 0 >= sizeof($arguments) )
                throw new Exception('Value not set.');

            $field = Model::uncamelize( substr($name, 3) );
            $value = array_shift($arguments);

            $this->__set($field, $value);

            return $this;
        }

        throw new Exception("Call to undefined method `$name`.");
    }

    /**
     * @param integer $id
     * @return boolean
     */
    public function get( $id )
    {
        if ( null === $this->identifier )
            throw new Exception('Identifier not found.');

        $result = $this->db->fetchRow(
            $this->db->select('*', $this->table, array(
                $this->identifier => $id
            ))
        );

        if ( $result )
        {
            foreach ( $result as $name => $value )
                $this->{":$name"} = $value;
        }

        $this->hash = Model::hash(
            $this->properties
        );

        return (boolean) $result;
    }

    /**
     * @return integer
     */
    public function put()
    {
        if ( null === $this->identifier )
            throw new Exception('Identifier not found.');

        $fields = array();
        $update = $this->properties[$this->identifier]->has();
        $return = 0;

        foreach ( $this->properties as $name => $instance )
        {
            if ( !( is_subclass_of($instance, 'Property') ) )
                continue;

            if ( $instance instanceof PropertyIdentifier )
                continue;

            $fields[$name] = $instance->get();
        }

        if ( $update )
        {
            $return = $affected = $this->db->update($this->table, $fields, array(
                $this->identifier => $this->properties[$this->identifier]->get()
            ));
        }
        else
        {
            $return = $inserted = $this->db->insert($this->table, $fields);

            if ( $inserted )
                $this->properties[$this->identifier]->set($inserted);
        }

        $this->hash = Model::hash(
            $this->properties
        );

        return $return;
    }

    /**
     * @return integer
     */
    public function delete()
    {
        if ( null === $this->identifier )
            throw new Exception('Identifier not found.');

        $affected = $this->db->delete($this->table, array(
            $this->identifier => $this->properties[$this->identifier]->get()
        ));

        $this->clear();

        return $affected;
    }

    /**
     * @param string $class
     * @return string
     */
    public static function table( $class )
    {
        return strtolower($class);
    }

    /**
     * @param string $word
     * @return string
     */
    public static function camelize( $word )
    {
        return str_replace(' ', '', ucwords( str_replace('_', ' ', $word) ) );
    }

    /**
     * @param string $word
     * @return string
     */
    public static function uncamelize( $word )
    {
        return substr( strtolower( preg_replace('/([A-Z])/', '_$1', $word) ), 1);
    }

    /**
     * @param array $properties
     * @return string
     */
    public static function hash( array $properties )
    {
        $fields = array();

        foreach ( $properties as $name => $instance )
        {
            if ( !( is_subclass_of($instance, 'Property') ) )
                continue;

            $fields[] = $name . ':' . $instance->get();
        }

        return md5( implode(';', $fields) );
    }
}