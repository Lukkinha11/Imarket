<?php

namespace App\Model\Product;

interface Product_Repository 
{
    public function getProducts();
    public function getProduct($id);
    //public function getPagination();
}