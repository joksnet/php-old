<?php

class Model
{
    protected $table;
    protected $index;

    protected $values = array();
    protected $found = false;

    public function __construct( $id = null )
    {
        $this->table = self::_table( get_class($this) );

        $indexes = Db::fetchAssoc(
            "SHOW INDEX FROM $this->table"
        );

        $this->index = $indexes[0]['Column_name'];

        if ( !( is_null($id) ) )
        {
            $this->id = $id;
            $this->query();
        }
    }

    public function __set( $name, $value )
    {
        $this->values[$name] = $value;
    }

    public function __get( $name )
    {
        try {
            return $this->values[$name];
        } catch ( Exception $a ) {
            throw new Exception("Field \"$name\" not found;");
        }
    }

    public function __isset( $name )
    {
        return isset($this->values[$name]);
    }

    public function __unset( $name )
    {
        unset($this->values[$name]);
    }

    public function found( $set = null )
    {
        if ( !( is_null($set) ) )
            $this->found = $set;

        return $this->found;
    }

    public function query()
    {
        $this->found = false;

        if ( !( isset($this->values['id']) ) )
            return false;

        $values = Db::fetchRow(
            Db::select('*', $this->table, array( $this->index => $this->values['id'] ), null, 1)
        );

        if ( $values )
        {
            $this->import($values);
            $this->found = true;
        }

        return $values ? true : false;
    }

    public function queryAll()
    {
        $this->found = false;

        $results = Db::fetchAssoc(
            Db::select('*', $this->table, $this->values)
        );

        if ( count($results) != 1 )
            return false;

        $this->import($results[0]);
        $this->found = true;

        return true;
    }

    public function insert()
    {
        if ( isset($this->values['id']) )
            return false;

        return Db::insert(
            $this->table,
            $this->values
        );
    }

    public function update()
    {
        $values = $this->values;

        if ( !( isset($values['id']) ) )
            return false;

        $id = $values['id'];
        unset($values['id']);

        return Db::update($this->table, $values, array(
            $this->index => $id
        ));
    }

    public function delete()
    {
        if ( !( isset($this->values['id']) ) )
            return false;

        $id = $this->values['id'];

        return Db::delete($this->table, array(
            $this->index => $id
        ));
    }

    protected function import( $values )
    {
        foreach ( $values as $name => $value )
        {
            if ( $name == $this->index )
                $name = 'id';

            $this->{$name} = $value;
        }
    }

    protected static function _table( $class )
    {
        return strtolower($class);
    }

    protected static function _one( $class, $id )
    {
        return new $class($id);
    }

    protected static function _all( $class, $where = null, $order = null, $limit = null )
    {
        $return = array();
        $result = Db::fetchAssoc(
            Db::select('*', self::_table($class), $where, $order, $limit)
        );

        if ( !( $result ) )
            return array();

        foreach ( $result as $row )
        {
            $instance = new $class();
            $instance->import($row);
            $instance->found(true);

            $return[] = $instance;
        }

        return $return;
    }

    protected static function _count( $class, $where = null )
    {
        return Db::count(
            self::_table($class), $where
        );
    }
}