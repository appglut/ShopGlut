<?php

namespace Shopglut\enhancements\Filters\implementation;

/**
 * ShopGlut Layout Page Filter Implementation
 * Simply display saved filters on ShopGlut layout pages
 */

class ShopPageShopglut {

    private $filter_id;
    private $filter_settings;

    public function __construct($filter_id, $filter_settings = []) {
        $this->filter_id = $filter_id;
        $this->filter_settings = $filter_settings;
    }

    
}