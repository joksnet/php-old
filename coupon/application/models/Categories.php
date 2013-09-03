<?php

class Categories extends Model
{
    public function init()
    {
        $this->category_id = new PropertyIdentifier();
        $this->name        = new PropertyString(80);
        $this->active      = new PropertyBoolean(true);
        $this->modified    = new PropertyDate(true);
        $this->created     = new PropertyDate(false, true);
    }

    /**
     * @return Query
     */
    public static function query()
    {
        return new Query(__CLASS__);
    }

    /**
     * Return a multiple array with categories and subcategories based on a
     * string separator in the category name.
     *
     * @example
     * $categories = Categories::query()
     *     ->order('name')
     *     ->limit($per, ( $page - 1 ) * $per)
     *     ->fetch();
     * $tree = Categories::tree($categories);
     *
     * @param array $categories
     * @param string $separator
     * @return array
     */
    public static function tree( $categories, $separator = ' - ' )
    {
        $tree = array();

        foreach ( $categories as $category )
        {
            $parts = explode($separator, $category->name);
            $partsTree = & $tree;

            foreach ( $parts as $part )
            {
                if ( !( isset($partsTree[$part]) ) )
                {
                    $partsTree[$part] = array(
                        'category' => $category,
                        'children' => array()
                    );
                }

                $partsTree = & $partsTree[$part]['children'];
            }
        }

        return $tree;
    }
}