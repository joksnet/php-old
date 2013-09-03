<?php

class Businesses_Categories extends Model
{
    public function init()
    {
        $this->business_category_id = new PropertyIdentifier();
        $this->business_id          = new PropertyRelation('Businesses');
        $this->category_id          = new PropertyRelation('Categories');
    }
}