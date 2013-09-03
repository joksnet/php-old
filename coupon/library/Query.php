<?php

class Query
{
    const ORDER_ASC = 'ASC';
    const ORDER_DESC = 'DESC';

    /**
     * @var Db
     */
    protected $db;

    protected $class;
    protected $table;

    protected $where = array();
    protected $order = array();
    protected $limit = array();

    /**
     * @param string $table
     */
    public function __construct( $class )
    {
        $this->db = Db::getInstance();

        $this->class = $class;
        $this->table = Model::table($this->class);
    }

    /**
     * @return Query
     */
    public function filter( $field, $value )
    {
        $this->where[$field] = $value; return $this;
    }

    /**
     * @return Query
     */
    public function order( $field, $order = Query::ORDER_ASC )
    {
        $this->order[$field] = $order; return $this;
    }

    /**
     * @return Query
     */
    public function limit( $limit, $offset = 0 )
    {
        $this->limit = array(
            $offset, $limit
        );

        return $this;
    }

    /**
     * @return array
     */
    public function fetch()
    {
        $query = $this->db->select('*', $this->table, $this->where, $this->order, $this->limit);
        $results = $this->db->fetchAssoc($query);
        $return = array();

        if ( $results )
        {
            foreach ( $results as $result )
            {
                $item = new $this->class();

                foreach ( $result as $name => $value )
                    $item->{":$name"} = $value;

                $return[] = $item;
            }
        }

        return $return;
    }

    /**
     * @return integer
     */
    public function count()
    {
        return $this->db->count(
            $this->table,
            $this->where
        );
    }
}