<?php

namespace App\Controller;

use App\Mvc\Controller;
use App\Model\Product\Product_Repository;

class Home extends Controller
{
    private $product;
    private $pagina;

    public function __construct(Product_Repository $product)//$pagi
    {
        parent::__construct();
        $this->product = $product;
        //$this->pagi = $pagi;
    }

    public function index()
    {
        $this->view->set('products', $this->product->getProducts());
        $this->view->render('home');
    }


    
}