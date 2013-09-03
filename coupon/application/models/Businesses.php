<?php

class Businesses extends Model
{
    public function init()
    {
        $this->business_id         = new PropertyIdentifier();
        $this->key                 = new PropertyString(20);
        $this->name                = new PropertyString(80);
        $this->address_addr1       = new PropertyString(100);
        $this->address_addr2       = new PropertyString(100);
        $this->address_addr3       = new PropertyString(100);
        $this->address_city        = new PropertyString(80);
        $this->address_province    = new PropertyString(80);
        $this->address_postal_code = new PropertyString(10);
        $this->country             = new PropertyString(80);
        $this->phone_main          = new PropertyString(20);
        $this->phone_mobile        = new PropertyString(20);
        $this->phone_fax           = new PropertyString(20);
        $this->active              = new PropertyBoolean(true);
        $this->modified            = new PropertyDate(true);
        $this->created             = new PropertyDate(false, true);
    }

    /**
     * @return Query
     */
    public static function query()
    {
        return new Query(__CLASS__);
    }
}