<?php

class Admin_Businesses_Add extends Controller
{
    public function init()
    {
        if ( !( $this->session->login ) )
            return '/admin/login';

        return true;
    }

    public function get()
    {
        include_once 'application/models/Categories.php';

        return array(
            'categories' => Categories::query()->fetch()
        );
    }

    public function post()
    {
        include_once 'application/models/Businesses.php';
        include_once 'application/models/Businesses/Categories.php';

        $key  = $this->request->getPost('key');
        $name = $this->request->getPost('name');

        if ( empty($name) )
            return false;

        $categories = array();
        $categoriesCount = $this->request->getPost('categories_count', 0);

        for ( $i = 0; $i < $categoriesCount; $i++ )
        {
            $category = $this->request->getPost('category_' . $i);

            if ( $category > 0 )
                $categories[$i] = $category;
        }

        $addressAddr1 = $this->request->getPost('address_addr1');
        $addressAddr2 = $this->request->getPost('address_addr2');
        $addressAddr3 = $this->request->getPost('address_addr2');

        if ( empty($addressAddr1) )
            return false;

        $addressCity       = $this->request->getPost('address_city');
        $addressProvince   = $this->request->getPost('address_province');
        $addressPostalCode = $this->request->getPost('address_postal_code');

        if ( empty($addressCity) || empty($addressPostalCode) )
            return false;

        $country = $this->request->getPost('country');

        if ( empty($country) )
            return false;

        $phoneMain     = $this->request->getPost('phone_main');
        $phoneMobile   = $this->request->getPost('phone_mobile');
        $phoneTollfree = $this->request->getPost('phone_tollfree');
        $phoneFax      = $this->request->getPost('phone_fax');

        $business = new Businesses();
        $business->key  = $key;
        $business->name = $name;

        $business
            ->setAddressAddr1($addressAddr1)
            ->setAddressAddr2($addressAddr2)
            ->setAddressAddr3($addressAddr3)
            ->setAddressCity($addressCity)
            ->setAddressProvince($addressProvince)
            ->setAddressPostalCode($addressPostalCode);

        $business->country = $country;

        $business
            ->setPhoneMain($phoneMain)
            ->setPhoneMobile($phoneMobile)
          //->setPhoneTollfree($phoneTollfree)
            ->setPhoneFax($phoneFax);

        $business->put();

        foreach ( $categories as $category )
        {
            $businessCategory = new Businesses_Categories();
            $businessCategory
                ->setBusinessId($business->id)
                ->setCategoryId($category)
                ->put();
        }

        return "/admin/businesses/$business->id";
    }
}