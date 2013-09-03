<?php

class PropertyRelation extends Property
{
    protected $relation = '';

    public function __construct( $relation )
    {
        parent::__construct();

        if ( !( is_string($relation) ) )
            throw new Exception('Relation must be the Model class name.');
        elseif ( !( class_exists($relation) ) )
            include_once 'application/models/' . str_replace('_', '/', $relation) . '.php';

        $this->relation = $relation;
        $this->length   = 8;
    }

    protected function name()
    {
        return 'MEDIUMINT';
    }

    public function validate( $value )
    {
        if ( is_integer($value) )
            return intval($value);
        elseif ( is_subclass_of($value, 'Model') )
            return $value->id;

        throw new Exception(
            'Relation must be an integer or a Model instance.'
        );
    }
}